<?php

namespace Database\Seeders;

use App\Models\Settings\SocialiteSetting;
use Illuminate\Database\Seeder;

class SocialiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialiteSettings = [
            [
                'provider' => 'linkedin',
                'client_id' => '86rqzisqgyenbe',
                'client_secret' => 'WPL_AP1.ftIHuGh5w5lbdjhd.pbtMMw==',
                'redirect_url' => route('auth.linkedin.callback'),
                'status' => true,
            ],
            [
                'provider' => 'google',
                'client_id' => '1041820112911-pkn1mc6gq7au68mdk8dlop3bf8j049t3.apps.googleusercontent.com',
                'client_secret' => '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',
                'redirect_url' => route('auth.google.callback'),
                'status' => true,
            ],
        ];

        foreach ($socialiteSettings as $setting) {
            SocialiteSetting::updateOrCreate(
                ['provider' => $setting['provider']],
                $setting
            );
        }
    }
}
