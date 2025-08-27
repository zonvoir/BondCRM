<?php

namespace App\Services\Mails;

use App\Models\Settings\Imap;
use App\Models\SmtpUser;
use App\Models\User;
use Carbon\Carbon;
use DateTimeInterface;
use Exception;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Webklex\PHPIMAP\Attribute;
use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Exceptions\ConnectionFailedException;
use Webklex\PHPIMAP\Exceptions\EventNotFoundException;
use Webklex\PHPIMAP\Exceptions\FolderFetchingException;
use Webklex\PHPIMAP\Exceptions\GetMessagesFailedException;
use Webklex\PHPIMAP\Exceptions\InvalidMessageDateException;
use Webklex\PHPIMAP\Exceptions\MessageContentFetchingException;
use Webklex\PHPIMAP\Exceptions\MessageFlagException;
use Webklex\PHPIMAP\Exceptions\MessageHeaderFetchingException;
use Webklex\PHPIMAP\Exceptions\MessageNotFoundException;
use Webklex\PHPIMAP\Exceptions\RuntimeException;
use Webklex\PHPIMAP\Folder;
use Webklex\PHPIMAP\Message;

class ImapFetcher
{
    public function __construct(public User $user) {}

    /**
     * @throws RuntimeException
     * @throws GetMessagesFailedException
     * @throws ConnectionFailedException
     * @throws FolderFetchingException
     */
    public function fetchToday(object $settings, string $folderName = 'INBOX'): array
    {
        $client = $this->makeConnectedClient($settings);
        $mailbox = $this->resolveMailbox($client, $folderName);
        $messages = $mailbox->query()->all()->setFetchOrderDesc()->get();

        $emails = collect();
        foreach ($messages as $msg) {
            $dateAttr = $msg->getDate();

            if (! ($dateAttr instanceof Attribute)) {
                continue;
            }

            $dateValue = $dateAttr->get();

            if (is_array($dateValue)) {
                $dateValue = reset($dateValue);
            }

            if (! ($dateValue instanceof DateTimeInterface)) {
                continue;
            }

            $msgDate = Carbon::instance($dateValue)->setTimezone(config('app.timezone'));

            if (! $msgDate->isToday()) {
                continue;
            }

            $rawFrom = $msg->getFrom()[0] ?? null;
            $emails->push([
                'id' => $msg->getUid(),
                'sender_name' => $rawFrom->personal ?? $rawFrom->mail ?? '',
                'sender_email' => $rawFrom->mail ?? '',
                'cc' => $this->extractEmailAddresses($msg->getCc()),
                'bcc' => $this->extractEmailAddresses($msg->getBcc()),
                'subject' => $msg->getSubject() ?? '(No Subject)',
                'body' => $msg->getTextBody() ?? $msg->getHtmlBody() ?? '(No Body)',
                'created_at' => $msgDate->toDate(),
            ]);
        }

        return $emails->values()->toArray();
    }

    /**
     * @throws Exception
     */
    public function fetch(string $folderName = 'INBOX', $provider = null): Collection
    {
        $settings = $this->getSettings($provider);

        try {
            $client = $this->makeConnectedClient($settings);
            $mailbox = $this->resolveMailbox($client, $folderName);
            $messages = $mailbox->query()->all()->setFetchOrderDesc()->get();

            return collect($messages)->map(function ($msg) {
                $rawFrom = $msg->getFrom()[0] ?? null;

                return [
                    'id' => $msg->getUid(),
                    'messages_id' => $msg->getUid(),
                    'sender_name' => $rawFrom->personal ?? $rawFrom->mail ?? '',
                    'sender_email' => $msg->getFrom()[0]?->mail ?? null,
                    'cc' => $this->extractEmailAddresses($msg->getCc()),
                    'bcc' => $this->extractEmailAddresses($msg->getBcc()),
                    'subject' => e($msg->getSubject()) ?? '(No Subject)',
                    'body' => $msg->getTextBody(),
                    'created_at' => humanTime($msg->getDate()?->toDate()),
                ];
            })->values();

        } catch (Exception $e) {
            throw new Exception('IMAP fetch failed: '.$e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function deleteEmails($data, $type): bool
    {
        if (empty(count($data['ids'])) && empty($data['folder'])) {
            return false;
        }

        try {
            $settings = $this->getSettings($type);
            $client = $this->makeConnectedClient($settings);
            $mailbox = $this->resolveMailbox($client, $data['folder']);

            foreach ($data['ids'] as $uid) {
                $msg = $mailbox->query()->uid($uid)->get()->first();
                if ($msg) {
                    $msg->delete();
                }
            }

            $client->expunge();

            return true;
        } catch (Exception $e) {
            throw new Exception('Email deletion failed: '.$e->getMessage());
        }
    }

    /**
     * @throws RuntimeException
     * @throws MessageFlagException
     * @throws EventNotFoundException
     * @throws MessageContentFetchingException
     * @throws FolderFetchingException
     * @throws ConnectionFailedException
     * @throws InvalidMessageDateException
     * @throws MessageHeaderFetchingException
     * @throws MessageNotFoundException
     */
    public function getReplyData($provider, string $folderName, int $emailUid): array
    {
        $settings = $this->getSettings($provider);

        $client = $this->makeConnectedClient($settings);
        $mailbox = $this->resolveMailbox($client, $folderName);

        $message = $mailbox->query()->getMessageByUid($emailUid);

        $from = $message->getFrom()[0] ?? null;
        $ccList = $message->getCc() ?: [];
        $bccList = $message->getBcc() ?: [];

        $email = [
            'id' => $message->getUid(),
            'sender_email' => $from->mail ?? '',
            'sender_name' => $from->personal ?? $from->mail ?? '',
            'subject' => $message->getSubject(),
            'body' => $message->getHtmlBody() ?? $message->getTextBody(),
            'cc' => $this->extractEmailAddresses($ccList),
            'bcc' => $this->extractEmailAddresses($bccList),
            'created_at' => humanTime($message->getDate()),
            'avatar' => $from->mail ?? '',
        ];

        $makeTags = fn (array $list) => array_map(fn ($addr) => [
            'value' => $addr,
            'name' => $addr,
            'avatar' => $addr,
        ], $list);

        return [
            'email' => $email,
            'toTags' => $makeTags([$email['sender_email']]),
            'ccTags' => $makeTags($email['cc']),
            'bccTags' => $makeTags($email['bcc']),
            'subject' => 'Re: '.$email['subject'],
            'subject_title' => ''.$email['subject'],
            'emailUid' => $email['id'],
            'created_at' => humanTime($message->getDate()),
            'attachments' => [],
        ];
    }

    /**
     * @throws RuntimeException
     * @throws EventNotFoundException
     * @throws MessageFlagException
     * @throws MessageContentFetchingException
     * @throws FolderFetchingException
     * @throws ConnectionFailedException
     * @throws InvalidMessageDateException
     * @throws MessageHeaderFetchingException
     * @throws MessageNotFoundException
     */
    public function getMessageByUid($provider, string $folder, int $emailUid): Message
    {
        $settings = $this->getSettings($provider);
        $client = $this->makeConnectedClient($settings);
        $mailbox = $this->resolveMailbox($client, $folder);

        return $mailbox->query()->getMessageByUid($emailUid);
    }

    /**
     * @throws Exception
     */
    public function sendReplyEmail($originalEmail, $replyData, $attachments = []): void
    {
        try {
            $smtpSettings = SmtpUser::query()->where('user_id', $this->user->id)->first();
            $this->configureSmtp($smtpSettings);
            $body = $this->sanitizeHtml($replyData);
            $to = $this->extractFirstEmail($originalEmail->from);
            $cc = $this->extractEmails($originalEmail->cc);
            $bcc = $this->extractEmails($originalEmail->bcc);
            $subject = 'Re: '.($originalEmail->subject ?? '');

            $this->sendReply($to, $body, $attachments, $cc, $bcc, $subject);

        } catch (Exception $e) {
            throw new Exception('Failed to send reply: '.$e->getMessage());
        }
    }

    protected function makeConnectedClient(object $settings): Client
    {
        $client = (new ClientManager())->make([
            'host' => $settings->imap_server,
            'port' => $settings->imap_port,
            'encryption' => $settings->imap_encryption,
            'validate_cert' => true,
            'username' => $settings->imap_user_name,
            'password' => $settings->password,
            'protocol' => 'imap',
        ]);
        $client->connect();

        return $client;
    }

    /**
     * @throws RuntimeException
     * @throws ConnectionFailedException
     * @throws FolderFetchingException
     * @throws Exception
     */
    protected function resolveMailbox(Client $client, string $folder): Folder
    {
        if ($folderObj = $client->getFolder(mb_strtoupper($folder))) {
            return $folderObj;
        }

        $flat = $this->flattenFolders($client->getFolders(true));
        foreach ($flat as $f) {
            if (str_contains(mb_strtolower($f->path), mb_strtolower($folder))) {
                return $f;
            }
        }

        throw new Exception("Folder $folder not found.");
    }

    protected function flattenFolders(iterable $folders): array
    {
        $flat = [];
        foreach ($folders as $f) {
            $flat[] = $f;
            if (! empty($f->children)) {
                $flat = array_merge($flat, $this->flattenFolders($f->children));
            }
        }

        return $flat;
    }

    protected function extractEmailAddresses(mixed $addresses): array
    {
        if (! $addresses instanceof Attribute) {
            return [];
        }

        return collect($addresses->get())->pluck('mail')->filter()->values()->toArray();
    }

    protected function getSettings($provider): ?Imap
    {
        return Imap::query()->where(['user_id' => Auth::id(), 'type' => $provider])->first() ?? new Imap();
    }

    private function configureSmtp(SmtpUser $smtp)
    {
        Config::set([
            'mail.default' => $smtp->mail_driver,
            'mail.mailers.smtp' => [
                'transport' => $smtp->mail_driver,
                'host' => $smtp->mail_host,
                'port' => $smtp->mail_port,
                'encryption' => $smtp->mail_encryption ?: null,
                'username' => $smtp->mail,
                'password' => $smtp->password,
                'timeout' => null,
                'auth_mode' => null,
            ],
            'mail.from.address' => $smtp->mail_from_address,
            'mail.from.name' => $smtp->mail_from_name,
        ]);

        app()->forgetInstance(Mailer::class);
    }

    private function sanitizeHtml($html): string
    {
        $purifier = new HTMLPurifier(HTMLPurifier_Config::createDefault());

        return $purifier->purify($html);
    }

    private function extractFirstEmail($addresses)
    {
        $emails = $this->extractEmails($addresses);

        return $emails[0] ?? null;
    }

    private function extractEmails($attribute): array
    {
        $emails = [];
        if ($attribute && method_exists($attribute, 'get')) {
            foreach ($attribute->get() as $address) {
                if (isset($address->mail) && filter_var($address->mail, FILTER_VALIDATE_EMAIL)) {
                    $emails[] = $address->mail;
                }
            }
        }

        return $emails;
    }

    private function sendReply($to, $body, $attachments, $cc, $bcc, $subject)
    {
        Mail::send([], [], function ($message) use ($to, $body, $attachments, $cc, $bcc, $subject) {
            $message->to($to)
                ->subject($subject)
                ->html($body);

            if (! empty($cc)) {
                $message->cc($cc);
            }

            if (! empty($bcc)) {
                $message->bcc($bcc);
            }

            if (! empty($attachments)) {
                foreach ($attachments as $attachment) {
                    $message->attach($attachment['path'], [
                        'as' => $attachment['name'],
                        'mime' => mime_content_type($attachment['path']),
                    ]);
                }
            }
        });
    }
}
