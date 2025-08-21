<?php

namespace App\Services\Settings;

use App\Models\Settings\GeneralSettings;
use App\Models\Settings\LiveChatSettings;
use App\Models\Settings\SocialiteSetting;
use App\Traits\FileUploadTrait;
use EragLaravelPwa\Facades\PWA;
use Illuminate\Http\UploadedFile;

class SettingsService
{
    use FileUploadTrait;

    public function saveGeneralSettings(array $data)
    {
        $generalSettings = GeneralSettings::query()->first();

        if (isset($data['iconLogoDark']) && $data['iconLogoDark'] instanceof UploadedFile) {
            $uploadPath = storage_path('app/public/logo');
            $data['iconLogoDark'] = $this->uploadFile(
                $data['iconLogoDark'],
                $uploadPath,
                $generalSettings?->icon_logo_dark,
                time().'_icon_logo_dark.png'
            );
        } else {
            $data['iconLogoDark'] = $generalSettings?->icon_logo_dark;
        }

        if (isset($data['iconLogoWhite']) && $data['iconLogoWhite'] instanceof UploadedFile) {
            $uploadPath = storage_path('app/public/logo');
            $data['iconLogoWhite'] = $this->uploadFile(
                $data['iconLogoWhite'],
                $uploadPath,
                $generalSettings?->icon_logo_white,
                time().'_icon_logo_white.png'
            );
        } else {
            $data['iconLogoWhite'] = $generalSettings?->icon_logo_white;
        }

        if (isset($data['logoDark']) && $data['logoDark'] instanceof UploadedFile) {
            $uploadPath = storage_path('app/public/logo');
            $data['logoDark'] = $this->uploadFile(
                $data['logoDark'],
                $uploadPath,
                $generalSettings?->logo_dark,
                time().'_logo_dark.png'
            );
        } else {
            $data['logoDark'] = $generalSettings?->logo_dark;
        }

        if (isset($data['logoWhite']) && $data['logoWhite'] instanceof UploadedFile) {
            $uploadPath = storage_path('app/public/logo');
            $data['logoWhite'] = $this->uploadFile(
                $data['logoWhite'],
                $uploadPath,
                $generalSettings?->logo_white,
                time().'logo_white.png'
            );
        } else {
            $data['logoWhite'] = $generalSettings?->logo_white;
        }

        if (isset($data['favicon']) && $data['favicon'] instanceof UploadedFile) {
            $uploadPath = storage_path('app/public/logo');
            $data['favicon'] = $this->uploadFile(
                $data['favicon'],
                $uploadPath,
                $generalSettings?->favicon,
                time().'_favicon.png'
            );
        } else {
            $data['favicon'] = $generalSettings?->favicon;
        }

        $this->saveSettings(
            [
                'footer_text' => $data['footerText'],
                'icon_logo_dark' => $data['iconLogoDark'],
                'icon_logo_white' => $data['iconLogoWhite'],
                'logo_dark' => $data['logoDark'],
                'logo_white' => $data['logoWhite'],
                'favicon' => $data['favicon'],
                'countries' => $data['countries'],
                'timezones' => $data['timezones'],
            ]
        );
    }

    public function savePwaSettings(array $data)
    {

        $generalSettings = GeneralSettings::query()->first();

        if (isset($data['appLogo']) && $data['appLogo'] instanceof UploadedFile) {
            $uploadPath = public_path();
            $data['appLogo'] = $this->uploadFile(
                $data['appLogo'],
                $uploadPath,
                $generalSettings?->app_logo,
                'logo.png'
            );
        } else {
            $data['appLogo'] = $generalSettings?->app_logo;
        }

        $this->saveSettings(
            [
                'app_name' => $data['appName'],
                'app_description' => $data['description'],
                'app_logo' => 'logo.png',
                'theme_color' => $data['themeColor'],
            ]
        );

        PWA::update([
            'name' => $data['appName'],
            'short_name' => getFirstAndLastWord($data['appName']),
            'background_color' => '#6777ef',
            'display' => 'fullscreen',
            'description' => $data['description'],
            'theme_color' => $data['themeColor'],
            'icons' => [
                [
                    'src' => 'logo.png',
                    'sizes' => '512x512',
                    'type' => 'image/png',
                ],
            ],
        ]);
    }

    public function saveSocialiteSettings(array $data)
    {
        $providers = [
            'linkedin' => [
                'client_id' => $data['linkedinClientId'] ?? '',
                'client_secret' => $data['linkedinClientSecret'] ?? '',
                'redirect_url' => $data['linkedinRedirect'] ?? '',
                'status' => $data['statusLinkedin'] ?? '',
            ],
            'google' => [
                'client_id' => $data['googleClientId'] ?? '',
                'client_secret' => $data['googleClientSecret'] ?? '',
                'redirect_url' => $data['googleRedirect'] ?? '',
                'status' => $data['statusGoogle'] ?? '',
            ],
        ];

        foreach ($providers as $provider => $credentials) {
            SocialiteSetting::query()->updateOrCreate(
                ['provider' => $provider],
                $credentials
            );
        }
    }

    public function saveChatSettings(array $data)
    {
        $generalSettings = LiveChatSettings::query()->first();

        $data['type'] = $data['type']['code'];

        return LiveChatSettings::query()->updateOrCreate(
            ['id' => $generalSettings?->id],
            $data
        );
    }

    protected function saveSettings(array $data)
    {
        $generalSettings = GeneralSettings::query()->first();

        return GeneralSettings::query()->updateOrCreate(
            ['id' => $generalSettings?->id],
            $data
        );
    }
}
