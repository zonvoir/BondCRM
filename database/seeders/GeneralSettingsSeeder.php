<?php

namespace Database\Seeders;

use App\Models\Settings\GeneralSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GeneralSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $paths = [
            'favicon' => public_path('assets/images/favicon.png'),
            'icon_logo_dark' => public_path('assets/images/inboxSyncLogo.png'),
            'icon_logo_white' => public_path('assets/images/inboxSyncLogoWhite.png'),
            'logo_dark' => public_path('assets/images/inboxSync.png'),
            'logo_white' => public_path('assets/images/inboxSyncWhite.png'),
            'app_logo' => public_path('assets/images/inboxSyncLogo.png'),
        ];

        $uploaded = [];

        foreach ($paths as $key => $fullPath) {
            if (! File::exists($fullPath)) {
                return;
            }

            $fileName = basename($fullPath);
            $storagePath = 'logo/'.$fileName;

            if (Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->delete($storagePath);
            }

            $storedPath = Storage::disk('public')->putFileAs('', $fullPath, $storagePath);
            $uploaded[$key] = basename($storedPath);
        }

        GeneralSettings::create([
            'footer_text' => 'footerText',
            'icon_logo_dark' => $uploaded['icon_logo_dark'],
            'icon_logo_white' => $uploaded['icon_logo_white'],
            'logo_dark' => $uploaded['logo_dark'],
            'logo_white' => $uploaded['logo_white'],
            'favicon' => $uploaded['favicon'],
            'app_name' => 'InboxSync App',
            'app_description' => 'Email Marketing App',
            'app_logo' => 'logo.png',
            'theme_color' => '#6777ef',
            'countries' => json_encode(['India', 'USA', 'UK']),
            'timezones' => json_encode(['code' => 'Asia/Kolkata', 'name' => 'Asia/Kolkata']),
        ]);

    }
}
