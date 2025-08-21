<?php

namespace App\Services\Settings;

use App\Models\Settings\EmailSetting;
use App\Models\Settings\GeneralSettings;
use App\Notifications\TestEmailConfiguration;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;

class EmailSettingsService
{
    public function createEmailSetting(array $data)
    {

        $emailSetting = EmailSetting::query()->first();

        return EmailSetting::query()->updateOrCreate(
            ['id' => $emailSetting->id ?? null],
            [
                'mail_driver' => $data['mailDriver'],
                'mail_host' => $data['mailHost'],
                'mail_port' => $data['mailPort'],
                'mail' => $data['mail'],
                'password' => $data['password'],
                'mail_encryption' => $data['mailEncryption'],
                'mail_from_address' => $data['mailFromAddress'],
                'mail_from_name' => $data['mailFromName'],
            ]
        );
    }

    public function testEmailSetting(array $data)
    {
        $settings = GeneralSettings::query()->select('app_name')->first();
        Config::set('app.name', $settings->app_name ?? 'App');

        App::getInstance()->register('App\Providers\MailConfigProvider');
        Notification::route('mail', [$data['email'] => 'demo'])->notify(new TestEmailConfiguration());

    }
}
