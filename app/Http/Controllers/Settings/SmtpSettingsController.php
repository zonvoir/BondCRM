<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Email\TestEmailSettingRequest;
use App\Http\Requests\Settings\EmailSettingsRequest;
use App\Services\Settings\EmailSettingsService;

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
