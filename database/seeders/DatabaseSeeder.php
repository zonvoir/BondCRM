<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GeneralSettingsSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
            SocialiteSettingsSeeder::class,
            EmailSettingSeeder::class,
        ]);

    }
}
