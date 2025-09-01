<?php

namespace App\Services\Setup;

use App\Models\Setup\GeneralSettings;
use App\Models\Setup\LiveChatSettings;
use App\Models\Setup\Setting;
use App\Models\Setup\SocialiteSetting;
use App\Traits\FileUploadTrait;
use EragLaravelPwa\Facades\PWA;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;

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

        $this->generalSettingsSave(
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

        $this->generalSettingsSave(
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

    public function saveMicrosoftSocialiteSettings(array $data)
    {
        SocialiteSetting::query()->updateOrCreate(
            ['provider' => 'microsoft'],
            [
                'client_id' => $data['microsoftClientId'] ?? '',
                'client_secret' => $data['microsoftClientSecret'] ?? '',
                'redirect_url' => $data['microsoftRedirect'] ?? '',
                'status' => $data['statusMicrosoft'] ?? '',
            ]
        );
    }

    public function saveGoogleSocialiteSettings(array $data)
    {
        SocialiteSetting::query()->updateOrCreate(
            ['provider' => 'google'],
            [
                'client_id' => $data['googleClientId'] ?? '',
                'client_secret' => $data['googleClientSecret'] ?? '',
                'redirect_url' => $data['googleRedirect'] ?? '',
                'status' => $data['statusGoogle'] ?? '',
            ]
        );

    }

    public function saveChatSettings(array $data): Model|LiveChatSettings
    {
        $generalSettings = LiveChatSettings::query()->first();

        $data['type'] = $data['type']['code'];

        return LiveChatSettings::query()->updateOrCreate(
            ['id' => $generalSettings?->id],
            $data
        );
    }

    public function getValueSettings(string $group, string $key, $default = null)
    {
        $settings = Cache::remember("settings:{$group}", 60, function () use ($group) {
            return Setting::query()->where('group', $group)->get()->keyBy('key');
        });

        if (! isset($settings[$key])) {
            return $default;
        }

        $record = $settings[$key];
        $value = $record->value;

        return match ($record->type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true),
            default => $value, // string
        };
    }

    public function saveSettings(string $group, string $key, $value): Model|Setting
    {
        $type = match (true) {
            is_bool($value) => 'boolean',
            is_int($value) => 'integer',
            is_float($value) => 'float',
            is_array($value),
            is_object($value) => 'json',
            default => 'string',
        };

        $serialized = match ($type) {
            'boolean' => $value ? '1' : '0',
            'json' => json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            default => (string) $value,
        };

        $setting = Setting::query()->updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['type' => $type, 'value' => $serialized]
        );

        Cache::forget("settings:{$group}");

        return $setting;
    }

    protected function generalSettingsSave(array $data): Model|GeneralSettings
    {
        $generalSettings = GeneralSettings::query()->first();

        return GeneralSettings::query()->updateOrCreate(
            ['id' => $generalSettings?->id],
            $data
        );
    }
}
