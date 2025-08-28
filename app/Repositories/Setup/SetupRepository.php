<?php

namespace App\Repositories\Setup;

use App\Models\Setup\Imap;
use App\Models\Setup\LiveChatSettings;
use App\Models\Setup\OpenAiSetting;
use App\Models\Setup\SmtpSetting;
use App\Models\Setup\SmtpUser;
use App\Models\Setup\SocialiteSetting;

class SetupRepository
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
        return Imap::query()->where(['user_id' => auth()->id(), 'type' => $type])->first() ?? new Imap();
    }

    public function getSmtpUserSettings(): ?SmtpUser
    {
        return SmtpUser::query()->where('user_id', auth()->id())->first() ?? new SmtpUser();
    }

    public function getOpenAiSettings(): OpenAiSetting
    {
        return OpenAiSetting::query()->first() ?? new OpenAiSetting();

    }
}
