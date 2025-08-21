<?php

namespace App\Providers;

use App\Models\Settings\EmailSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class MailConfigProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('email_settings')) {
            $emailSettings = EmailSetting::first();
            if (! empty($emailSettings)) {
                Config::set('mail.default', $emailSettings->mail_driver);
                Config::set('mail.mailers.smtp.host', $emailSettings->mail_host);
                Config::set('mail.mailers.smtp.port', $emailSettings->mail_port);
                Config::set('mail.mailers.smtp.username', $emailSettings->mail);
                Config::set('mail.mailers.smtp.password', $emailSettings->password);
                Config::set('mail.mailers.smtp.encryption', $emailSettings->mail_encryption);
                Config::set('mail.from.address', $emailSettings->mail_from_address);
                Config::set('mail.from.name', $emailSettings->mail_from_name);
            }
        }
    }
}
