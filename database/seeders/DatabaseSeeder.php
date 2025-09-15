<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Lead;
use App\Models\Setup\Sources;
use App\Models\Setup\Status;
use Faker\Factory as Faker;
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
            CountrySeeder::class,
            StatusSeeder::class,
            SourcesSeeder::class,
        ]);

        $faker = Faker::create();

        $statuses = Status::query()->whereNotIn('name', ['Lost', 'Junk'])->get();
        $sources = Sources::all();
        $countries = Country::all();

        $sourceIds = $sources->pluck('id')->toArray();
        $statusIds = $statuses->pluck('id')->toArray();
        $countryIds = $countries->pluck('id')->toArray();

        // Create 50 fake leads
        for ($i = 0; $i < 500; $i++) {
            $isDateContacted = $faker->boolean(30); // 30% chance of being contacted

            Lead::query()->create([
                'user_id' => 1, // Assuming user with ID 1 exists
                'name' => $faker->name(),
                'sources_id' => $faker->randomElement($sourceIds),
                'status_id' => $faker->randomElement($statusIds),
                'address' => $faker->address(),
                'position' => $faker->jobTitle(),
                'city' => $faker->city(),
                'email' => $faker->unique()->safeEmail(),
                'state' => $faker->state(),
                'website' => $faker->optional(0.7)->url(), // 70% chance of having a website
                'country_id' => $faker->randomElement($countryIds),
                'phone' => $faker->randomNumber(9, true), // 9-digit phone number
                'zip_code' => $faker->randomNumber(5, true), // 5-digit zip code
                'lead_value' => $faker->randomFloat(2, 1000, 100000), // Lead value between 1000 and 100000
                'company' => $faker->company(),
                'description' => $faker->optional(0.8)->paragraph(), // 80% chance of having description
                'date_contacted' => $isDateContacted ? $faker->dateTimeBetween('-30 days', 'now') : null,
                'public' => $faker->boolean(70), // 70% chance of being public
                'is_date_contacted' => $isDateContacted,
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 month', 'now'),
            ]);
        }

    }
}
