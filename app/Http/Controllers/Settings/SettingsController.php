<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setup\GeneralSettingsRequest;
use App\Http\Requests\Setup\LiveChatSettingsRequest;
use App\Http\Requests\Setup\PwaSettingsRequest;
use App\Http\Requests\Setup\SettingsSocialiteRequest;
use App\Repositories\Setup\SetupRepository;
use App\Services\Setup\SettingsService;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    public function __construct(protected SettingsService $settingsService, protected SetupRepository $SettingsRepository) {}

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
