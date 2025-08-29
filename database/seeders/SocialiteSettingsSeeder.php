<?php

namespace Database\Seeders;

use App\Models\Setup\SocialiteSetting;
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
                'provider' => 'microsoft',
                'client_id' => 'b428d529-fae6-441b-9352-d1528494d033',
                'client_secret' => '7So8Q~LO111j6F..wTXzLfs.C.Sen-rQVugYFcIw',
                'redirect_url' => 'https://fwd.host/'.route('auth.microsoft.callback'),
                'status' => true,
            ],
            [
                'provider' => 'google',
                'client_id' => '907512095123-q4j831ms6b4vsc12tiooibq261bmsnmc.apps.googleusercontent.com',
                'client_secret' => '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',
                'redirect_url' => 'https://fwd.host/'.route('auth.google.callback'),
                'status' => true,
            ],
        ];

        foreach ($socialiteSettings as $setting) {
            SocialiteSetting::query()->updateOrCreate(
                ['provider' => $setting['provider']],
                $setting
            );
        }
    }
}