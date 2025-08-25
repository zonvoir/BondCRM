<?php

namespace App\Repositories\Settings;

use App\Models\Settings\Imap;
use App\Models\Settings\LiveChatSettings;
use App\Models\Settings\SmtpSetting;
use App\Models\Settings\SocialiteSetting;

class SettingsRepository
{
    public function getEmailSettings(): SmtpSetting
    {
        return SmtpSetting::query()->first() ?? new SmtpSetting();
    }

    public function getSocialiteSettings($provider): SocialiteSetting
    {
        return SocialiteSetting::query()->whereProvider($provider)->first() ?? new SocialiteSetting();
    }

    public function getChatSettings(): LiveChatSettings
    {
        return LiveChatSettings::query()->first() ?? new LiveChatSettings();
    }

    public function getImapSettings(string $type): ?Imap
    {
        return Imap::query()->where(['user_id' => auth()->id(), 'type' => $type])->first();
    }
}
