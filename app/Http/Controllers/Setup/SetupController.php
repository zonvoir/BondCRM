<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ImapRequest;
use App\Repositories\Settings\SettingsRepository;
use App\Services\Setup\SetupService;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class SetupController extends Controller
{
    public function __construct(protected SetupService $setupService, protected SettingsRepository $settings) {}

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

    public function imapSettings($type)
    {
        $props = [
            'smtpType' => $type,
            'settings' => $this->settings->getImapSettings($type),
        ];

        return Inertia::render('SettingsEmployee/Imap', $props);
    }

    public function saveImapSettings(ImapRequest $request)
    {
        $this->setupService->saveImap($request->validated());

        return back()->with([
            'message' => 'Save successfully',
            'type' => 'success',
        ]);
    }
}
