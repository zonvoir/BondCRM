<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\GeneralSettingsRequest;
use App\Http\Requests\Settings\LiveChatSettingsRequest;
use App\Http\Requests\Settings\PwaSettingsRequest;
use App\Http\Requests\Settings\SettingsSocialiteRequest;
use App\Http\Resources\Settings\EmailSettingsResource;
use App\Http\Resources\Settings\GeneralSettingsResource;
use App\Http\Resources\Settings\LiveChatSettingsResource;
use App\Http\Resources\Settings\SocialiteSettingsResource;
use App\Models\Settings\GeneralSettings;
use App\Repositories\Settings\SettingsRepository;
use App\Services\Settings\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function __construct(protected SettingsService $settingsService, protected SettingsRepository $SettingsRepository) {}

    public function show(Request $request)
    {
        $props = [
            'timezones' => timezones(),
            'generalSettings' => new GeneralSettingsResource(GeneralSettings::first()),
            'emailSettings' => new EmailSettingsResource($this->SettingsRepository->getEmailSettings()),
            'liveChatSettings' => new LiveChatSettingsResource($this->SettingsRepository->getChatSettings()),
            'socialiteSettings' => [
                'linkedin' => new SocialiteSettingsResource($this->SettingsRepository->getSocialiteSettings('linkedin')),
                'google' => new SocialiteSettingsResource($this->SettingsRepository->getSocialiteSettings('google')),
            ],
        ];

        return Inertia::render('Settings/index', $props);
    }

    public function generalSettingsSave(GeneralSettingsRequest $request)
    {
        $this->settingsService->saveGeneralSettings($request->validated());

        return back()->with([
            'message' => 'Updated successfully',
            'type' => 'success',
        ]);
    }

    public function settingsPwaSave(PwaSettingsRequest $request)
    {
        $this->settingsService->savePwaSettings($request->validated());

        return back()->with([
            'message' => 'Updated successfully',
            'type' => 'success',
        ]);
    }

    public function settingsSocialiteSave(SettingsSocialiteRequest $request)
    {
        $this->settingsService->saveSocialiteSettings($request->validated());

        return back()->with([
            'message' => 'Updated successfully',
            'type' => 'success',
        ]);
    }

    public function settingsChatSave(LiveChatSettingsRequest $request)
    {
        $this->settingsService->saveChatSettings($request->validated());

        return back()->with([
            'message' => 'Updated successfully',
            'type' => 'success',
        ]);
    }

    public function clearCache()
    {
        Artisan::call('optimize:clear');
        sleep(1);

        return response()->json(200);
    }

    public function storageLink()
    {
        Artisan::call('storage:link');
        sleep(1);

        return response()->json(200);

    }

    public function runCron()
    {
        Artisan::call('schedule:run');
        sleep(1);

        return response()->json(200);

    }
}
