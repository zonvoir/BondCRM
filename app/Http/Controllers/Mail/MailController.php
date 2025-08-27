<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mail\GmailReplyRequest;
use App\Http\Requests\Mail\OutlookReplyRequest;
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

    public function gmailInboxView(Request $request)
    {
        $gmail = new GmailService(auth()->user());
        $data = $gmail->gmailReply($request->id);

        if (! $data['success']) {
            return false;
        }

        return $data;
    }

    public function gmailMailReply(GmailReplyRequest $request)
    {
        $data = $request->validated();
        $result = app(GmailService::class, ['user' => auth()->user()])
            ->gmailReplySend($data, $data['id']);

        $msg = $result ? 'Reply sent successfully' : 'Failed to send gmail reply';
        $type = $result ? 'success' : 'error';

        return redirect(route('employee.gmail', 'inbox'))->with([
            'message' => $msg,
            'type' => $type,
        ]);

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

    public function outlookInboxView(Request $request)
    {
        if (empty($request->id)) {
            return false;
        }

        $graph = app(GraphMicrosoftService::class, ['user' => auth()->user()]);

        return $graph->getOutlookEmailById($request->id);
    }

    public function outlookMailReply(OutlookReplyRequest $request)
    {
        $validated = $request->validated();

        $graph = app(GraphMicrosoftService::class, ['user' => auth()->user()]);
        $result = $graph->sendOutlookReply($request->id, $validated);

        $msg = $result ? 'Reply sent successfully' : 'Failed to send outlook reply';
        $type = $result ? 'success' : 'error';

        return redirect(route('employee.outlook', 'inbox'))->with([
            'message' => $msg,
            'type' => $type,
        ]);

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

    public function webmailInboxView(Request $request)
    {
        return (new ImapFetcher(auth()->user()))->getReplyData('webmail', $request->folder, $request->id);
    }

    public function deleteMailSmtp(Request $request, $type)
    {
        $response = (new ImapFetcher(auth()->user()))->deleteEmails($request->all(), $type);

        $msg = $response ? 'deleted successfully' : 'Failed to deleted webmail';
        $status = $response ? 'success' : 'error';

        return back()->with([
            'message' => $msg,
            'type' => $status,
        ]);

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

    public function appleMailInboxView(Request $request)
    {
        return (new ImapFetcher(auth()->user()))->getReplyData('applemail', $request->folder, $request->id);
    }

    public function webMailReply(Request $request, $type, $folder)
    {
        if (empty($request->message_id)) {
            return false;
        }

        $message = (new ImapFetcher(auth()->user()))->getMessageByUid($type, $folder, $request->message_id);
        (new ImapFetcher(auth()->user()))->sendReplyEmail($message, $request->replyData, $request->attachments);

        return back()->with([
            'message' => 'sent',
            'type' => 'success',
        ]);
    }
}
