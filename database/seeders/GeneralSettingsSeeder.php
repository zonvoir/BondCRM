<?php

namespace Database\Seeders;

use App\Models\Setup\GeneralSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GeneralSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $paths = [
            'favicon' => public_path('assets/images/favicon.png'),
            'icon_logo_dark' => public_path('assets/images/logo-circle.png'),
            'icon_logo_white' => public_path('assets/images/logo-circle-white.png'),
            'logo_dark' => public_path('assets/images/logo.png'),
            'logo_white' => public_path('assets/images/logo-white.png'),
            'app_logo' => public_path('assets/images/logo.png'),
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

        GeneralSettings::query()->create([
            'icon_logo_dark' => $uploaded['icon_logo_dark'],
            'icon_logo_white' => $uploaded['icon_logo_white'],
            'logo_dark' => $uploaded['logo_dark'],
            'logo_white' => $uploaded['logo_white'],
            'favicon' => $uploaded['favicon'],
            'company_name' => 'Bond crm pvt. ltd',
            'allowed_file_types' => '.png,.jpg,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.txt',
            'app_name' => 'Bond CRM',
            'app_description' => 'Email Marketing App',
            'app_logo' => 'logo.png',
            'theme_color' => '#6777ef',
            'countries' => ['code' => 'AU', 'name' => 'Australia'],
            'date_format' => ['code' => 'd-m-Y', 'name' => 'd-m-Y'],
            'time_format' => ['code' => '12', 'name' => '12 hours'],
            'timezones' => ['code' => 'Asia/Kolkata', 'name' => 'Asia/Kolkata'],
        ]);

    }
}
