<?php

namespace App\Services\Mails;

use App\Models\Settings\SocialiteSetting;
use App\Models\SocialCredential;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class GmailService
{
    private const GMAIL_API_BASE = 'https://gmail.googleapis.com/gmail/v1/users/me';

    private const OAUTH_TOKEN_URL = 'https://oauth2.googleapis.com/token';

    public function __construct(public User $user) {}

    public function getFolderMessages(string $folder, int $length, ?string $pageToken = null, ?string $search = '', ?string $filter = null, ?string $startDate = null, ?string $endDate = null): array
    {
        $credential = $this->getCredential();
        if (! $credential) {
            return ['error' => 'Please connect your Gmail account first.'];
        }

        $token = $this->ensureValidToken($credential);
        if (! $token) {
            return ['error' => 'Failed to refresh or obtain token.'];
        }

        return match ($folder) {
            'draft' => $this->getDraftMessages($token, $length, $pageToken),
            'trash' => $this->getTrashMessages($token, $length, $pageToken, $search),
            'sent' => $this->getSentMessages($token, $length, $pageToken),
            default => $this->getInboxMessages($token, $length, $pageToken, $search, $filter, $startDate, $endDate),
        };
    }

    public function getTrashMessages(string $token, int $length, ?string $pageToken = null, ?string $search = ''): array
    {
        $query = 'label:trash'.(! empty($search) ? " {$search}" : '');
        $params = ['maxResults' => $length, 'q' => $query];
        if ($pageToken) {
            $params['pageToken'] = $pageToken;
        }

        $listData = $this->fetchMessages($token, $params);
        if (empty($listData['messages'])) {
            return ['data' => [], 'total' => 0];
        }

        $data = $this->processMessages($token, $listData['messages'], 'trash');
        $labelInfo = $this->fetchLabelInfo($token, 'TRASH');

        return [
            'data' => $data,
            'total' => $labelInfo ?? count($data),
            'nextPageToken' => $listData['nextPageToken'] ?? null,
        ];
    }

    public function fetchMessages(string $token, array $params): array
    {
        return $this->makeApiRequest($token, '/messages', $params);
    }

    public function fetchMessageDetail(string $token, string $messageId): array
    {
        return $this->makeApiRequest($token, "/messages/{$messageId}", ['format' => 'full']);
    }

    public function refreshToken(SocialCredential $credential)
    {
        $socialiteSetting = SocialiteSetting::query()->where('provider', 'google')->first();
        $response = Http::asForm()->post(self::OAUTH_TOKEN_URL, [
            'client_id' => $socialiteSetting->client_id ?? null,
            'client_secret' => $socialiteSetting->client_secret ?? null,
            'refresh_token' => $credential->refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        return $response->json();
    }

    public function ensureValidToken(SocialCredential $credential): ?string
    {
        $newToken = $this->refreshToken($credential);

        if ($newToken) {
            $credential->update([
                'access_token' => $newToken['access_token'],
                'token_expires_at' => now()->addSeconds($newToken['refresh_token_expires_in']),
            ]);

            return $newToken['access_token'];
        }

        return null;
    }

    public function getCredential(): ?SocialCredential
    {
        return SocialCredential::query()
            ->where('user_id', $this->user?->id)
            ->where('provider', 'gmail')
            ->first();
    }

    public function getDraftMessages(string $token, int $length, ?string $pageToken = null): array
    {
        $response = $this->fetchDrafts($token, $length, $pageToken);
        if (empty($response['drafts'])) {
            return ['data' => [], 'total' => 0];
        }

        $data = [];

        foreach ($response['drafts'] as $draft) {
            if (empty($draft['id'])) {
                continue;
            }

            $fullDraft = $this->fetchDraftDetail($token, $draft['id']);
            $msgData = $fullDraft['message'] ?? null;
            if (! $msgData || ! isset($msgData['payload'])) {
                continue;
            }

            $headers = collect($msgData['payload']['headers']);
            $from = $this->parseEmailHeader($headers->toArray(), 'from');
            $subject = $headers->firstWhere('name', 'Subject')['value'] ?? 'No Subject';
            $formattedDate = humanTime($headers->firstWhere('name', 'Date')['value']);
            $msgId = $msgData['id'];

            $data[] = $this->buildMessageData($msgData, $msgId, $from, $subject, $formattedDate);
        }

        return [
            'data' => $data,
            'total' => $response['resultSizeEstimate'] ?? count($data),
            'nextPageToken' => $response['nextPageToken'] ?? null,
        ];
    }

    public function fetchDrafts(string $token, int $length, ?string $pageToken = null): array
    {
        $params = ['maxResults' => $length];
        if ($pageToken) {
            $params['pageToken'] = $pageToken;
        }

        return $this->makeApiRequest($token, '/drafts', $params);
    }

    public function fetchDraftDetail(string $token, string $draftId): array
    {
        return $this->makeApiRequest($token, "/drafts/{$draftId}", ['format' => 'full']);
    }

    public function parseEmailHeader(array $headers, string $type = 'from'): array
    {
        $headerName = ucfirst($type);
        $headerValue = collect($headers)->firstWhere('name', $headerName)['value'] ?? 'Unknown';

        preg_match('/^(.*?)(?:\s*<(.+?)>)?$/', $headerValue, $matches);

        return [
            'name' => trim($matches[1] ?? 'Unknown'),
            'email' => trim($matches[2] ?? $matches[1] ?? 'Unknown'),
        ];
    }

    public function decodeMessageBody(array $msgData): ?string
    {
        if (! isset($msgData['payload'])) {
            return '';
        }

        $bodyText = '';
        $payload = $msgData['payload'];

        if (! empty($payload['parts'])) {
            $bodyText = $this->extractBodyFromParts($payload['parts']);
        } elseif (isset($payload['body']['data'])) {
            $bodyText = $this->decodeBase64UrlSafe($payload['body']['data']);
        }

        if (empty($bodyText)) {
            return $msgData['snippet'] ?? null;
        }

        return $bodyText;
    }

    public function gravatarInfo(string $email): array
    {
        $hash = md5(mb_strtolower(trim($email)));
        $url = "https://www.gravatar.com/avatar/{$hash}?s=100&d=404";
        $exists = @get_headers($url)[0] !== 'HTTP/1.1 404 Not Found';

        return [
            'url' => $url,
            'exists' => $exists,
            'initial' => mb_strtoupper(mb_substr($email, 0, 1)),
        ];
    }

    public function truncateEmail(string $email, int $maxLength = 15): string
    {
        return mb_strlen($email) > $maxLength
            ? mb_substr($email, 0, $maxLength).'...'
            : $email;
    }

    public function getSentMessages(string $token, int $length, ?string $pageToken = null): array
    {
        $params = ['maxResults' => $length, 'q' => 'label:sent'];
        if ($pageToken) {
            $params['pageToken'] = $pageToken;
        }

        $listData = $this->fetchMessages($token, $params);
        if (empty($listData['messages'])) {
            return ['data' => [], 'total' => 0];
        }

        $data = $this->processMessages($token, $listData['messages'], 'sent');
        $labelInfo = $this->fetchLabelInfo($token, 'SENT');

        return [
            'data' => $data,
            'total' => $labelInfo ?? ($listData['resultSizeEstimate'] ?? count($data)),
            'nextPageToken' => $listData['nextPageToken'] ?? null,
        ];
    }

    public function fetchLabelInfo(string $token, string $labelId): ?int
    {
        $response = $this->makeApiRequest($token, "/labels/{$labelId}");

        return $response['messagesTotal'] ?? null;
    }

    public function getInboxMessages(string $token, int $length, ?string $pageToken = null, ?string $search = '', ?string $filter = null, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = $this->buildQuery('label:INBOX (is:unread OR is:read)', $search, $filter, $startDate, $endDate);

        $params = ['maxResults' => $length, 'q' => $query];
        if ($pageToken) {
            $params['pageToken'] = $pageToken;
        }

        $listData = $this->fetchMessages($token, $params);

        if (empty($listData['messages'])) {
            return ['data' => [], 'total' => 0];
        }

        $data = $this->processMessages($token, $listData['messages'], 'inbox');

        return [
            'data' => $data,
            'total' => $listData['resultSizeEstimate'] ?? count($data),
            'nextPageToken' => $listData['nextPageToken'] ?? null,
        ];
    }

    public function gmailReply(string $messageId): array
    {
        $credential = $this->getCredential();
        if (! $credential) {
            return ['error' => 'Please connect your Gmail account first.'];
        }

        $token = $this->ensureValidToken($credential);
        if (! $token) {
            return ['error' => 'Failed to refresh token.'];
        }

        $emailData = $this->fetchMessageDetail($token, $messageId);

        if (empty($emailData)) {
            return ['error' => 'Failed to retrieve email.'];
        }

        $headers = collect($emailData['payload']['headers']);

        $subjectHeader = $headers->firstWhere('name', 'Subject')['value'] ?? 'No Subject';
        $fromHeader = $headers->firstWhere('name', 'From')['value'] ?? 'Unknown';

        preg_match('/^(.*?)(?:\s*<(.+?)>)?$/', $fromHeader, $m);
        $senderName = trim($m[1] ?? '') ?: trim($m[2] ?? 'Unknown');
        $senderEmail = trim($m[2] ?? 'Unknown');

        $dateHeader = $headers->firstWhere('name', 'Date')['value'] ?? now()->toDateTimeString();
        $bodyText = $this->decodeMessageBody($emailData);

        // Extract attachments
        $attachments = $this->extractEmailAttachments($emailData, $token, $messageId);

        $to = $senderEmail;
        $cc = $headers->firstWhere('name', 'Cc')['value'] ?? '';
        $bcc = $headers->firstWhere('name', 'Bcc')['value'] ?? '';
        $subject = 'Re: '.$subjectHeader;

        return [
            'success' => true,
            'email' => [
                'id' => $messageId,
                'subject' => $subjectHeader,
                'sender_name' => $senderName,
                'sender_email' => $senderEmail,
                'created_at' => humanTime($dateHeader),
                'body' => $bodyText,
                'avatar' => $senderEmail,
                'threadId' => $emailData['threadId'] ?? null,
            ],
            'to' => $to,
            'cc' => $cc,
            'bcc' => $bcc,
            'subject' => $subject,
            'attachments' => $attachments,
        ];
    }

    public function gmailReplySend(array $data, string $messageId): bool
    {
        $credential = $this->getCredential();
        if (! $credential) {
            return false;
        }

        $token = $this->ensureValidToken($credential);
        if (! $token) {
            return false;
        }

        $original = $this->fetchMessageDetail($token, $messageId);
        $threadId = $original['threadId'] ?? null;
        $inReplyTo = $this->extractHeader($original, 'Message-ID');

        $encoded = $this->buildReplyMimeMessage($data, $inReplyTo);

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
            'Content-Type' => 'application/json',
        ])->post(self::GMAIL_API_BASE.'/messages/send', [
            'raw' => $encoded,
            'threadId' => $threadId,
        ]);

        return $response->successful();
    }

    private function processMessages(string $token, array $messages, string $folder = 'inbox'): array
    {
        $data = [];

        foreach ($messages as $msg) {
            $msgId = $msg['id'];
            $msgData = $this->fetchMessageDetail($token, $msgId);

            if (! isset($msgData['payload'])) {
                continue;
            }

            $headers = collect($msgData['payload']['headers']);
            $from = $this->parseEmailHeader($headers->toArray(), $folder === 'sent' ? 'to' : 'from');
            $subject = $headers->firstWhere('name', 'Subject')['value'] ?? 'No Subject';
            $formattedDate = humanTime($headers->firstWhere('name', 'Date')['value']);

            $labelIds = $msgData['labelIds'] ?? [];
            $isStarred = in_array('STARRED', $labelIds);

            $actions = [];

            if ($folder === 'sent') {
                $from = $this->processSentMessageRecipient($headers);
            }

            $data[] = $this->buildMessageData($msgData, $msgId, $from, $subject, $formattedDate, $actions, $isStarred);
        }

        return $data;
    }

    private function processSentMessageRecipient(object $headers): array
    {
        $toHeader = $headers->firstWhere('name', 'To')['value'] ?? 'Unknown Recipient';
        preg_match('/^(.*?)(?:\s*<(.+?)>)?$/', explode(',', $toHeader)[0], $matches);
        $recipientEmail = trim($matches[2] ?? $matches[1] ?? 'Unknown');
        $shortEmail = $this->truncateEmail($recipientEmail);

        return [
            'email' => $recipientEmail,
            'name' => 'To: '.strip_tags(e($recipientEmail)),
        ];
    }

    private function buildMessageData(
        array $msgData,
        string $msgId,
        array $from,
        string $subject,
        string $formattedDate,
        array $actions = [],
        bool $isStarred = false
    ): array {
        $gravatar = $this->gravatarInfo($from['email']);

        return [
            'id' => $msgId,
            'from' => [
                'name' => $from['name'],
                'email' => $from['email'],
                'avatar' => [
                    'exists' => (bool) ($gravatar['exists'] ?? false),
                    'url' => $gravatar['url'] ?? null,
                    'initial' => $gravatar['initial'] ?? null,
                ],
            ],
            'subject' => $subject,
            'created_at' => $formattedDate,
            'created_at_ts' => $msgData['timestamp'] ?? null,
            'is_starred' => $isStarred,
            'actions' => $actions,
            'body' => $this->decodeMessageBody($msgData),
        ];
    }

    private function extractBodyFromParts(array $parts): string
    {
        $bodyText = '';
        foreach ($parts as $k => $part) {
            if (! empty($part['body']['data'])) {
                if ($k === 0) {
                    continue;
                }

                $bodyText .= $this->decodeBase64UrlSafe($part['body']['data'])."\n";
            } elseif (! empty($part['parts'])) {
                $bodyText .= $this->extractBodyFromParts($part['parts']);
            }
        }

        return $bodyText;
    }

    private function decodeBase64UrlSafe(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    private function makeApiRequest(string $token, string $endpoint, array $params = []): array
    {
        $response = Http::withToken($token)->get(self::GMAIL_API_BASE.$endpoint, $params);

        return $response->successful() ? $response->json() : [];
    }

    private function buildQuery(string $baseQuery, ?string $search = null, ?string $filter = null, ?string $startDate = null, ?string $endDate = null): string
    {
        $query = $baseQuery;

        if ($filter === 'starred') {
            $query .= ' is:starred';
        } elseif ($filter === 'unstarred') {
            $query .= ' -is:starred';
        }

        if (! empty($search)) {
            $query .= " {$search}";
        }

        if ($startDate) {
            $startTimestamp = Carbon::parse($startDate)->startOfDay()->timestamp;
            $query .= " after:{$startTimestamp}";
        }

        if ($endDate) {
            $endTimestamp = Carbon::parse($endDate)->endOfDay()->timestamp;
            $query .= " before:{$endTimestamp}";
        }

        return $query;
    }

    private function extractEmailAttachments(array $emailData, string $token, string $messageId): array
    {
        $attachments = [];

        $extractAttachments = function ($parts) use (&$extractAttachments, &$attachments) {
            foreach ($parts as $p) {
                if (! empty($p['parts'])) {
                    $extractAttachments($p['parts']);
                }
                if (! empty($p['body']['attachmentId'])) {
                    $attachments[] = [
                        'name' => $p['filename'],
                        'contentType' => $p['mimeType'],
                        'attachmentId' => $p['body']['attachmentId'],
                        'size' => $p['body']['size'] ?? null,
                        'contentBytes' => null,
                    ];
                }
            }
        };

        if (! empty($emailData['payload']['parts'])) {
            $extractAttachments($emailData['payload']['parts']);
        }

        foreach ($attachments as &$att) {
            $attResp = Http::withHeaders(['Authorization' => "Bearer {$token}"])
                ->get(self::GMAIL_API_BASE."/messages/{$messageId}/attachments/{$att['attachmentId']}");
            if ($attResp->successful()) {
                $data = $attResp->json();
                $att['contentBytes'] = $this->decodeBase64UrlSafe($data['data']);
            }
        }
        unset($att);

        return $attachments;
    }

    private function extractHeader(?array $message, string $name): ?string
    {
        if (isset($message['payload']['headers'])) {
            foreach ($message['payload']['headers'] as $header) {
                if (strcasecmp($header['name'], $name) === 0) {
                    return $header['value'];
                }
            }
        }

        return null;
    }

    private function buildReplyMimeMessage(array $data, ?string $inReplyTo): string
    {
        $boundary = uniqid('boundary_', true);

        $raw = "MIME-Version: 1.0\r\n"
            ."Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n"
            ."To: {$data['to']}\r\n"
            ."Subject: {$data['subject']}\r\n";

        if ($inReplyTo) {
            $raw .= "In-Reply-To: {$inReplyTo}\r\nReferences: {$inReplyTo}\r\n";
        }

        $raw .= "\r\n--{$boundary}\r\n"
            ."Content-Type: text/html; charset=UTF-8\r\n"
            ."Content-Transfer-Encoding: 7bit\r\n\r\n"
            ."{$data['message']}\r\n";

        if (! empty($data['attachments']) && is_array($data['attachments'])) {
            foreach ($data['attachments'] as $file) {
                if ($file instanceof UploadedFile && $file->isValid()) {
                    $dataEncoded = chunk_split(base64_encode(file_get_contents($file->getRealPath())));
                    $name = $file->getClientOriginalName();
                    $mimeType = $file->getMimeType();

                    $raw .= "\r\n--{$boundary}\r\n"
                        ."Content-Type: {$mimeType}; name=\"{$name}\"\r\n"
                        ."Content-Disposition: attachment; filename=\"{$name}\"\r\n"
                        ."Content-Transfer-Encoding: base64\r\n\r\n"
                        ."{$dataEncoded}\r\n";
                }
            }
        }

        $raw .= "--{$boundary}--";

        return rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
    }
}
