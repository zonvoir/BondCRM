<?php

namespace App\Repositories\Settings;

use App\Models\Settings\EmailSetting;
use App\Models\Settings\LiveChatSettings;
use App\Models\Settings\SocialiteSetting;

class SettingsRepository
{
    public function getEmailSettings(): EmailSetting
    {
        return EmailSetting::query()->first() ?? new EmailSetting();
    }

    public function getSocialiteSettings($provider): SocialiteSetting
    {
        return SocialiteSetting::query()->whereProvider($provider)->first() ?? new SocialiteSetting();
    }

    public function getChatSettings(): LiveChatSettings
    {
        return LiveChatSettings::query()->first() ?? new LiveChatSettings();
    }
}
