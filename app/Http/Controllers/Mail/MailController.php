<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\Services\Mails\GmailService;
use App\Services\Mails\GraphMicrosoftService;
use App\Services\Mails\ImapFetcher;
use App\Services\Mails\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MailController extends Controller
{
    public function __construct(protected MailService $mailService) {}

    public function gmailList(Request $request, $folder)
    {
        $user = Auth::user();
        $length = (int) $request->input('length', 10);
        $pageToken = $request->input('pt');
        $search = mb_strtolower($request->input('search')['value'] ?? '');
        $filter = $request->input('filter', null);
        $service = new GmailService($user);

        $result = $service->getFolderMessages($folder, $length, $pageToken, $search, $filter);

        $props = [
            'mails' => $this->mailService->gmailPagination($result),
            'pageToken' => '&pt='.$result['nextPageToken'] ?? null,
        ];

        return Inertia::render('Mails/Gmail', $props);
    }

    public function outlookList(Request $request, $folder)
    {
        $user = Auth::user();

        $service = new GraphMicrosoftService($user);

        $length = (int) $request->input('length', 10);
        $search = mb_strtolower($request->input('search')['value'] ?? '');
        $pageToken = $request->input('pt');

        $response = $service->getPaginatedInboxMessages(
            top: $length,
            skip: $pageToken ? (int) $pageToken : null,
            search: $search ?: null,
            folder: $folder,
        );

        $nextPageToken = null;
        if (! empty($response['@odata.nextLink'])) {
            $query = parse_url($response['@odata.nextLink'], PHP_URL_QUERY);
            parse_str($query, $parsed);
            $nextPageToken = $parsed['$skip'] ?? $parsed['$skiptoken'] ?? null;
        }

        $props = [
            'mails' => $this->mailService->outlookPagination($response),
            'pageToken' => '&pt='.$nextPageToken,
        ];

        return Inertia::render('Mails/Outlook', $props);
    }

    public function webMailList(Request $request, $type)
    {
        $user = Auth::user();
        $mails = (new ImapFetcher($user))->fetch($type, 'webmail');

        $props = [
            'mails' => $this->mailService->pagination($mails),
        ];

        return Inertia::render('Mails/WebMail', $props);
    }

    public function appleMailList(Request $request, $type)
    {
        $user = Auth::user();
        $mails = (new ImapFetcher($user))->fetch($type, 'applemail');

        $props = [
            'mails' => $this->mailService->pagination($mails),
        ];

        return Inertia::render('Mails/AppleMail', $props);
    }
}
