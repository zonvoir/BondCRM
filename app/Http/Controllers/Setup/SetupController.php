<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Services\Setup\SetupService;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class SetupController extends Controller
{
    public function __construct(protected SetupService $setupService) {}

    public function employeeSetup()
    {
        $props = [
            'configurations' => $this->setupService->mailsConfigureLogo(),
        ];

        return Inertia::render('SettingsEmployee/Index', $props);
    }

    public function authorizedGmail()
    {
        return Socialite::driver('google')->scopes([
            'https://mail.google.com/',
            'https://www.googleapis.com/auth/gmail.readonly',
            'https://www.googleapis.com/auth/gmail.modify',
            'https://www.googleapis.com/auth/gmail.labels',
        ])
            ->with(['prompt' => 'consent', 'access_type' => 'offline'])
            ->redirect();
    }

    public function authorizedOutlook() {}
}
