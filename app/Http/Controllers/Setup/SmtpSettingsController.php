<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Black\TestEmailSettingRequest;
use App\Http\Requests\Setup\EmailSettingsRequest;
use App\Services\Setup\EmailSettingsService;

class SmtpSettingsController extends Controller
{
    public function __construct(protected EmailSettingsService $emailSettingsService) {}

    public function saveEmailSetting(EmailSettingsRequest $request)
    {
        $this->emailSettingsService->createEmailSetting($request->validated());

        return back()->with([
            'message' => 'Email setting updated successfully!',
            'type' => 'success',
        ]);
    }

    public function testEmailSetting(TestEmailSettingRequest $request)
    {
        $this->emailSettingsService->testEmailSetting($request->validated());

        return back()->with([
            'message' => 'Email test successfully!',
            'type' => 'success',
        ]);
    }
}
