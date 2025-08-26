<?php

namespace App\Services\Setup;

use App\Models\Settings\Imap;
use App\Models\SmtpUser;
use App\Models\SocialCredential;
use Illuminate\Database\Eloquent\Model;

class SetupService
{
    public function mailsConfigureLogo(): array
    {
        $gmail = $this->getSocialCredentials('gmail');
        $g = $this->jsonToArray($gmail->credentials);

        $outlook = $this->getSocialCredentials('outlook');
        $o = $this->jsonToArray($outlook->credentials);

        return [
            'Gmail' => [
                'routeName' => route('auth.login.socialite', 'google'),
                'icon' => asset('assets/icons/gmail.png'),
                'isConfigure' => (bool) $g['email'],
                'email' => $g['email'],
                'color' => 'bg-red-50',
            ],
            'Outlook' => [
                'routeName' => route('auth.login.socialite', 'outlook'),
                'icon' => asset('assets/icons/outlook.png'),
                'isConfigure' => (bool) $o['email'],
                'email' => $o['email'],
                'color' => 'bg-cyan-50',
            ],
            'WebMail' => [
                'routeName' => route('employee.imap.settings', 'webmail'),
                'icon' => asset('assets/icons/webmail.png'),
                'isConfigure' => (bool) $this->getImap('webmail')->type,
                'email' => $this->getImap('webmail')->imap_user_name,
                'color' => 'bg-blue-50',
            ],
            'AppleMail' => [
                'routeName' => route('employee.imap.settings', 'applemail'),
                'icon' => asset('assets/icons/apple.svg'),
                'isConfigure' => (bool) $this->getImap('applemail')->type,
                'email' => $this->getImap('applemail')->imap_user_name,
                'color' => 'bg-green-50',
            ],
        ];
    }

    public function saveImap(array $data): Model|Imap
    {
        return Imap::query()->updateOrCreate(
            [
                'user_id' => auth()->id(),
                'type' => $data['type'],
            ],
            [
                'imap_server' => $data['imap_server'],
                'imap_user_name' => $data['imap_user_name'],
                'password' => $data['password'],
                'imap_port' => $data['imap_port'],
                'folder' => $data['folder'],
                'imap_encryption' => $data['imap_encryption'],
                'type' => $data['type'],
                'user_id' => auth()->id(),
            ]
        );
    }

    public function getImap($type): Model|Imap
    {
        return Imap::query()->where(['user_id' => auth()->id(), 'type' => $type])->first() ?? new Imap();
    }

    public function getSocialCredentials($provider): SocialCredential|Imap
    {
        return SocialCredential::query()->where(['user_id' => auth()->id(), 'provider' => $provider])->first() ?? new SocialCredential();
    }

    public function jsonToArray(string $json): array
    {
        return json_decode($json, true);
    }

    public function saveSmtpUser($data): SmtpUser|Model
    {
        $smtp = SmtpUser::query()->where('user_id', auth()->id())->first();

        return SmtpUser::query()->updateOrCreate(
            ['id' => $smtp->id ?? null],
            array_merge($data, ['user_id' => auth()->id()])
        );
    }
}
