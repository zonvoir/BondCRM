<?php

namespace App\Services\Mails;

use App\Models\Settings\SocialiteSetting;
use App\Models\SocialCredential;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GraphMicrosoftService
{
    public function __construct(public ?User $user) {}

    public function fetchAllEmails(): array
    {
        $credential = $this->getCredential();
        if (! $credential) {
            return ['error' => 'Please connect your Outlook account to continue.'];
        }

        $token = $this->getValidAccessToken($credential);
        if (! $token) {
            return ['error' => 'Outlook session expired. Please reconnect.'];
        }

        $endpoint = 'https://graph.microsoft.com/v1.0/me/mailFolders/inbox/messages';
        $headers = $this->getHeaders($token);

        $startOfDay = Carbon::today()->toIso8601String();   // e.g., 2025-06-19T00:00:00Z
        $endOfDay = Carbon::tomorrow()->toIso8601String(); // e.g., 2025-06-20T00:00:00Z

        $params = [
            '$top' => 50,
            '$select' => 'id,subject,from,receivedDateTime,bodyPreview',
            '$filter' => "receivedDateTime ge {$startOfDay} and receivedDateTime lt {$endOfDay}",
            '$orderby' => 'receivedDateTime desc',
        ];

        $allMessages = [];
        $pageCount = 0;
        $maxPages = 10;

        try {
            do {
                $response = retry(3, function () use ($headers, $endpoint, $params) {
                    return Http::withHeaders($headers)
                        ->timeout(60)
                        ->get($endpoint, $params);
                }, 200);

                if (! $response->successful()) {
                    return ['error' => 'Failed to fetch emails: '.$response->body()];
                }

                $json = $response->json();
                $allMessages = array_merge($allMessages, $json['value'] ?? []);

                $nextLink = $json['@odata.nextLink'] ?? null;
                if ($nextLink) {
                    $endpoint = $nextLink;
                    $params = [];
                }

                $pageCount++;
            } while ($nextLink && $pageCount < $maxPages);
        } catch (RequestException $e) {
            Log::error('Graph API timeout or connection failure', [
                'user_id' => $this->user->id,
                'message' => $e->getMessage(),
            ]);

            return ['error' => 'Request to Outlook API timed out. Please try again later.'];
        }

        return $allMessages;
    }

    public function getPaginatedInboxMessages(int $top = 10, ?int $skip = null, ?string $search = null, $folder = 'inbox'): array
    {
        $request = request()->all();

        if (empty($request['start_date']) && empty($request['end_date'])) {
            $request = ['start_date' => null, 'end_date' => null];
        }

        $credential = $this->getCredential();
        if (! $credential) {
            return ['error' => 'Please connect your Outlook account to continue.'];
        }

        $token = $this->getValidAccessToken($credential);
        if (! $token) {
            return ['error' => 'Outlook session expired. Please reconnect.'];
        }

        $params = [
            '$top' => $top,
            '$select' => 'id,subject,from,receivedDateTime,bodyPreview',
            '$count' => 'true',
        ];

        if ($skip) {
            $params['$skip'] = $skip;
        }

        $filters = [];

        if ($search) {
            $filters[] = "contains(subject,'{$search}')";
        }

        if ($request['start_date']) {
            $startIso = Carbon::parse($request['start_date'])->startOfDay()->toIso8601String();
            $filters[] = "receivedDateTime ge {$startIso}";
        }

        if ($request['end_date']) {
            $endIso = Carbon::parse($request['end_date'])->endOfDay()->toIso8601String();
            $filters[] = "receivedDateTime le {$endIso}";
        }

        if (! empty($filters)) {
            $params['$filter'] = implode(' and ', $filters);
        }

        $response = Http::withHeaders($this->getHeaders($token))
            ->get('https://graph.microsoft.com/v1.0/me/mailFolders/'.$folder.'/messages', $params);

        if (! $response->successful()) {
            return ['error' => 'Failed to fetch messages: '.$response->body()];
        }

        return $response->json();
    }

    public function getOutlookEmailById(string $emailId): ?array
    {
        $credential = $this->getCredential();
        if (! $credential) {
            return null;
        }

        $token = $this->getValidAccessToken($credential);
        if (! $token) {
            return null;
        }

        $response = Http::withHeaders($this->getHeaders($token))->get(
            "https://graph.microsoft.com/v1.0/me/mailFolders/inbox/messages/{$emailId}",
            [
                '$select' => 'id,subject,from,receivedDateTime,body',
                '$expand' => 'attachments',
            ]
        );

        if (! $response->successful()) {
            Log::error('Failed to fetch Outlook email by ID', [
                'user_id' => $this->user->id,
                'email_id' => $emailId,
                'response' => $response->body(),
            ]);

            return null;
        }

        $email = $response->json();
        $from = $email['from']['emailAddress'] ?? [];
        $senderEmail = $from['address'] ?? 'Unknown';
        $senderName = $from['name'] ?? $senderEmail;

        $formattedDate = '';
        if (! empty($email['receivedDateTime'])) {
            $dt = Carbon::parse($email['receivedDateTime'])->setTimezone(config('app.timezone'));
            $formattedDate = $dt->isToday()
                ? $dt->format('H:i A')
                : $dt->format('M d');
        }

        return [
            'id' => $email['id'] ?? null,
            'subject' => $email['subject'] ?? '',
            'sender_email' => $senderEmail,
            'sender_name' => $senderName,
            'created_at' => $formattedDate,
            'body' => $email['body']['content'] ?? '',
            'avatar' => 'https://www.gravatar.com/avatar/'.md5(mb_strtolower(trim($senderEmail))).'?s=100&d=404',
            'attachments' => $email['attachments'] ?? [],
        ];
    }

    public function sendOutlookReply(string $emailId, array $replyData): bool
    {
        $credential = $this->getCredential();
        if (! $credential) {
            return false;
        }

        $token = $this->getValidAccessToken($credential);
        if (! $token) {
            return false;
        }

        $payload = [
            'comment' => $replyData['message'] ?? '',
        ];

        $response = Http::withHeaders($this->getHeaders($token))
            ->post("https://graph.microsoft.com/v1.0/me/messages/{$emailId}/reply", $payload);

        if (! $response->successful()) {
            Log::error('Failed to send Outlook reply', [
                'user_id' => $this->user->id,
                'email_id' => $emailId,
                'response' => $response->body(),
            ]);
        }

        return $response->successful();
    }

    private function getCredential(): ?SocialCredential
    {
        return SocialCredential::query()->where('user_id', $this->user->id)
            ->where('provider', 'outlook')
            ->first();
    }

    private function getValidAccessToken(SocialCredential $cred): ?string
    {
        if ($cred->token_expires_at && now()->lessThanOrEqualTo($cred->token_expires_at)) {
            return $cred->access_token;
        }

        return $this->refreshAccessToken($cred);
    }

    private function refreshAccessToken(SocialCredential $cred): ?string
    {
        $socialiteSetting = SocialiteSetting::query()->where('provider', 'google')->first();
        $response = Http::asForm()->post('https://login.microsoftonline.com/common/oauth2/v2.0/token', [
            'client_id' => $socialiteSetting->client_id,
            'client_secret' => $socialiteSetting->client_secret,
            'refresh_token' => $cred->refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();

        $cred->update([
            'access_token' => $data['access_token'],
            'token_expires_at' => now()->addSeconds(3600),
        ]);

        return $data['access_token'] ?? null;
    }

    private function getHeaders(string $token): array
    {
        return [
            'Authorization' => "Bearer {$token}",
            'Content-Type' => 'application/json',
            'ConsistencyLevel' => 'eventual',
        ];
    }
}
