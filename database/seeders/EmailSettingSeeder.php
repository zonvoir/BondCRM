<?php

namespace Database\Seeders;

use App\Models\Setup\Imap;
use App\Models\Setup\SmtpSetting;
use App\Models\Setup\SmtpUser;
use Illuminate\Database\Seeder;

class EmailSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SmtpSetting::query()->create([
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => '465',
            'mail' => 'gupta.amit2505@gmail.com',
            'password' => 'felz kbwz aklz jcvi',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'gupta.amit2505@gmail.com',
            'mail_from_name' => 'Bond CRM',
        ]);

        SmtpUser::query()->create([
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => '465',
            'mail' => 'gupta.amit2505@gmail.com',
            'password' => 'felz kbwz aklz jcvi',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'gupta.amit2505@gmail.com',
            'mail_from_name' => 'Bond CRM',
            'user_id' => 2,
        ]);

        $imaps = [
            [
                'imap_server' => 'zonvoirdemo.in',
                'imap_user_name' => 'amit@zonvoirdemo.in',
                'password' => 'Zum?U}7wFJYm',
                'imap_port' => '993',
                'folder' => 'INBOX',
                'imap_encryption' => 'SSL',
                'type' => 'webmail',
                'user_id' => 2,
            ],
            [
                'imap_server' => 'imap.mail.me.com',
                'imap_user_name' => 'rishabhsingh2219@icloud.com',
                'password' => 'tcww-xuge-wamf-upyk',
                'imap_port' => '993',
                'folder' => 'INBOX',
                'imap_encryption' => 'SSL',
                'type' => 'applemail',
                'user_id' => 2,
            ],
        ];

        foreach ($imaps as $imap) {
            Imap::query()->create($imap);
        }
    }
}