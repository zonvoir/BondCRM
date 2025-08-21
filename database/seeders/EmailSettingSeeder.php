<?php

namespace Database\Seeders;

use App\Models\Settings\EmailSetting;
use Illuminate\Database\Seeder;

class EmailSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailSetting::create([
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => '465',
            'mail' => 'info.teamdevgeek@gmail.com',
            'password' => 'eanv isnm rcsi fbmi',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'info.teamdevgeek@gmail.com',
            'mail_from_name' => 'InboxSyncApp',
        ]);
    }
}
