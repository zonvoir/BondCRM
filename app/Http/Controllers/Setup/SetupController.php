<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setup\OpenAiRequest;
use App\Http\Resources\Settings\EmailSettingsResource;
use App\Http\Resources\Settings\GeneralSettingsResource;
use App\Http\Resources\Settings\LiveChatSettingsResource;
use App\Http\Resources\Settings\SocialiteSettingsResource;
use App\Models\Setup\GeneralSettings;
use App\Repositories\Setup\SetupRepository;
use App\Services\Setup\SettingsService;
use App\Services\Setup\SetupService;
use Inertia\Inertia;

class SetupController extends Controller
{
    public function __construct(protected SettingsService $settingsService, protected SetupRepository $setupRepository, protected SetupService $setupService) {}

    public function generalSettings()
    {
        $props = [
            'timezones' => timezones(),
            'generalSettings' => new GeneralSettingsResource(GeneralSettings::query()->first()),
        ];

        return Inertia::render('Setup/GeneralSettings', $props);
    }

    public function pwaSettings()
    {
        $props = [
            'generalSettings' => new GeneralSettingsResource(GeneralSettings::first()),
        ];

        return Inertia::render('Setup/PwaSettings', $props);
    }

    public function smtpSettings()
    {
        $props = [
            'emailSettings' => new EmailSettingsResource($this->setupRepository->getEmailSettings()),
        ];

        return Inertia::render('Setup/SmtpSettings', $props);
    }

    public function socialSettings()
    {
        $props = [
            'socialiteSettings' => [
                'microsoft' => new SocialiteSettingsResource($this->setupRepository->getSocialiteSettings('microsoft')),
                'google' => new SocialiteSettingsResource($this->setupRepository->getSocialiteSettings('google')),
            ],
        ];

        return Inertia::render('Setup/SocialSettings', $props);
    }

    public function chatSettings()
    {
        $props = [
            'liveChatSettings' => new LiveChatSettingsResource($this->setupRepository->getChatSettings()),
        ];

        return Inertia::render('Setup/ChatSettings', $props);
    }

    public function openAiSettings()
    {
        $props = [
            'openAiSettings' => $this->setupRepository->getOpenAiSettings(),
        ];

        return Inertia::render('Setup/OpenAiSettings', $props);
    }

    public function openAiSaveSettings(OpenAiRequest $request)
    {
        $this->setupService->saveOpenAiSettings($request->validated());

        return back()->with([
            'message' => 'Save successfully',
            'type' => 'success',
        ]);
    }
}
