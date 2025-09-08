<?php

namespace Database\Seeders;

use App\Models\Setup\Sources;
use Illuminate\Database\Seeder;

class SourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = null;

        $sources = [
            // Digital Marketing Sources
            [
                'source' => 'Google Ads',
                'user_id' => $userId,
            ],
            [
                'source' => 'Facebook Ads',
                'user_id' => $userId,
            ],
            [
                'source' => 'Instagram Ads',
                'user_id' => $userId,
            ],
            [
                'source' => 'LinkedIn Ads',
                'user_id' => $userId,
            ],
            [
                'source' => 'Twitter Ads',
                'user_id' => $userId,
            ],
            [
                'source' => 'YouTube Ads',
                'user_id' => $userId,
            ],

            // SEO & Content Sources
            [
                'source' => 'Organic Search',
                'user_id' => $userId,
            ],
            [
                'source' => 'Blog',
                'user_id' => $userId,
            ],
            [
                'source' => 'Content Marketing',
                'user_id' => $userId,
            ],
            [
                'source' => 'SEO',
                'user_id' => $userId,
            ],

            // Social Media Sources
            [
                'source' => 'Facebook',
                'user_id' => $userId,
            ],
            [
                'source' => 'Instagram',
                'user_id' => $userId,
            ],
            [
                'source' => 'LinkedIn',
                'user_id' => $userId,
            ],
            [
                'source' => 'Twitter',
                'user_id' => $userId,
            ],
            [
                'source' => 'TikTok',
                'user_id' => $userId,
            ],

            // Email & Direct Sources
            [
                'source' => 'Email Marketing',
                'user_id' => $userId,
            ],
            [
                'source' => 'Newsletter',
                'user_id' => $userId,
            ],
            [
                'source' => 'Direct Traffic',
                'user_id' => $userId,
            ],
            [
                'source' => 'Direct Mail',
                'user_id' => $userId,
            ],

            // Referral Sources
            [
                'source' => 'Referral',
                'user_id' => $userId,
            ],
            [
                'source' => 'Word of Mouth',
                'user_id' => $userId,
            ],
            [
                'source' => 'Partner Referral',
                'user_id' => $userId,
            ],
            [
                'source' => 'Customer Referral',
                'user_id' => $userId,
            ],

            // Traditional Marketing
            [
                'source' => 'Radio',
                'user_id' => $userId,
            ],
            [
                'source' => 'Television',
                'user_id' => $userId,
            ],
            [
                'source' => 'Print Media',
                'user_id' => $userId,
            ],
            [
                'source' => 'Billboard',
                'user_id' => $userId,
            ],

            // Events & Trade Shows
            [
                'source' => 'Trade Show',
                'user_id' => $userId,
            ],
            [
                'source' => 'Conference',
                'user_id' => $userId,
            ],
            [
                'source' => 'Webinar',
                'user_id' => $userId,
            ],
            [
                'source' => 'Event',
                'user_id' => $userId,
            ],
            [
                'source' => 'Workshop',
                'user_id' => $userId,
            ],

            // Online Platforms
            [
                'source' => 'Website',
                'user_id' => $userId,
            ],
            [
                'source' => 'Landing Page',
                'user_id' => $userId,
            ],
            [
                'source' => 'Online Directory',
                'user_id' => $userId,
            ],
            [
                'source' => 'Review Site',
                'user_id' => $userId,
            ],

            // Sales Team Sources
            [
                'source' => 'Cold Call',
                'user_id' => $userId,
            ],
            [
                'source' => 'Cold Email',
                'user_id' => $userId,
            ],
            [
                'source' => 'Sales Team',
                'user_id' => $userId,
            ],
            [
                'source' => 'Walk-in',
                'user_id' => $userId,
            ],

            // Other Sources
            [
                'source' => 'Other',
                'user_id' => $userId,
            ],
            [
                'source' => 'Unknown',
                'user_id' => $userId,
            ],
        ];

        foreach ($sources as $source) {
            Sources::query()->create($source);
        }
    }
}
