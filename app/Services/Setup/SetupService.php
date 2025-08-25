<?php

namespace App\Services\Setup;

use App\Models\Settings\Imap;
use Illuminate\Database\Eloquent\Model;

class SetupService
{
    public function mailsConfigureLogo(): array
    {
        return [
            'Gmail' => [
                'routeName' => route('auth.login.socialite', 'google'),
                'icon' => asset('assets/icons/gmail.png'),
                'isConfigure' => true,
                'email' => 'demo@gmail.com',
                'color' => 'bg-red-50',
            ],
            'Outlook' => [
                'routeName' => route('auth.login.socialite', 'outlook'),
                'icon' => asset('assets/icons/outlook.png'),
                'isConfigure' => true,
                'email' => 'demo@gmail.com',
                'color' => 'bg-cyan-50',
            ],
            'WebMail' => [
                'routeName' => route('employee.imap.settings', 'webmail'),
                'icon' => asset('assets/icons/webmail.png'),
                'isConfigure' => true,
                'email' => 'demo@gmail.com',
                'color' => 'bg-blue-50',
            ],
            'AppleMail' => [
                'routeName' => route('employee.imap.settings', 'applemail'),
                'icon' => asset('assets/icons/apple.svg'),
                'isConfigure' => true,
                'email' => 'demo@gmail.com',
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
}
