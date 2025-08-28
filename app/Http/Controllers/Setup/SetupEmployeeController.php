<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setup\ImapRequest;
use App\Http\Requests\Setup\SmtpUserRequest;
use App\Repositories\Setup\SetupRepository;
use App\Services\Setup\SetupService;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class SetupEmployeeController extends Controller
{
    public function __construct(protected SetupService $setupService, protected SetupRepository $settings) {}

    public function employeeSetup()
    {
        $props = [
            'configurations' => $this->setupService->mailsConfigureLogo(),
        ];

        return Inertia::render('Setup/Employee/Index', $props);
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

        return Inertia::render('Setup/Employee/Imap', $props);
    }

    public function saveImapSettings(ImapRequest $request)
    {
        $this->setupService->saveImap($request->validated());

        return back()->with([
            'message' => 'Save successfully',
            'type' => 'success',
        ]);
    }

    public function smtp()
    {
        $props = [
            'smtpSettings' => $this->settings->getSmtpUserSettings(),
        ];

        return Inertia::render('Setup/Employee/Smtp', $props);
    }

    public function smtpSave(SmtpUserRequest $request)
    {
        $this->setupService->saveSmtpUser($request->validated());

        return back()->with([
            'message' => 'Save successfully',
            'type' => 'success',
        ]);
    }
}
