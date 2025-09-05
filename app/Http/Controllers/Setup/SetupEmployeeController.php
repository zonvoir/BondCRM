<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setup\ImapRequest;
use App\Http\Requests\Setup\SmtpUserRequest;
use App\Repositories\Setup\SetupRepository;
use App\Services\Setup\SetupService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class SetupEmployeeController extends Controller
{
    public function __construct(protected SetupService $setupService, protected SetupRepository $settings) {}

    public function settings(Request $request, $type)
    {
        $props = [
            'menuSettings' => $this->setupService->employeeMenuSettings(),
            'data' => $this->setupService->propsEmployeeGeneral($type),
        ];

        return Inertia::render('Setup/Employee/'.ucfirst($type), $props);

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

    public function saveImapSettings(ImapRequest $request)
    {
        $this->setupService->saveImap($request->validated());

        return back()->with([
            'message' => 'Save successfully',
            'type' => 'success',
        ]);
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
