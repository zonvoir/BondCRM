<?php

namespace App\Services\Setup;

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
                'routeName' => route('employee.authorized.gmail'),
                'icon' => asset('assets/icons/webmail.png'),
                'isConfigure' => true,
                'email' => 'demo@gmail.com',
                'color' => 'bg-blue-50',
            ],
            'AppleMail' => [
                'routeName' => route('employee.authorized.gmail'),
                'icon' => asset('assets/icons/apple.svg'),
                'isConfigure' => true,
                'email' => 'demo@gmail.com',
                'color' => 'bg-green-50',
            ],
        ];
    }
}
