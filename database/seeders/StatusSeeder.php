<?php

namespace Database\Seeders;

use App\Models\Setup\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = null;

        $statuses = [
            [
                'name' => 'Gmail',
                'color' => 'EA4335',   // Gmail Red
                'user_id' => $userId,
            ],
            [
                'name' => 'Outlook',
                'color' => '0078D4',   // Outlook Blue
                'user_id' => $userId,
            ],
            [
                'name' => 'Webmail',
                'color' => '34A853',   // Generic/Webmail Green
                'user_id' => $userId,
            ],
            [
                'name' => 'Apple Mail',
                'color' => '7D7D7D',   // Apple Mail Gray
                'user_id' => $userId,
            ],
            [
                'name' => 'New Lead',
                'color' => '3b82f6',  // Blue
                'user_id' => $userId,
            ],

            [
                'name' => 'Hot Lead',
                'color' => 'ef4444',  // Red
                'user_id' => $userId,
            ],
            [
                'name' => 'Lost',
                'color' => 'ef4444',  // Red
                'user_id' => $userId,
            ],

            [
                'name' => 'Contacted',
                'color' => 'f59e0b',  // Amber
                'user_id' => $userId,
            ],
            [
                'name' => 'Follow Up',
                'color' => '8b5cf6',  // Purple
                'user_id' => $userId,
            ],
            [
                'name' => 'Meeting Scheduled',
                'color' => '06b6d4',  // Cyan
                'user_id' => $userId,
            ],
            [
                'name' => 'Proposal Sent',
                'color' => '84cc16',  // Lime
                'user_id' => $userId,
            ],

            [
                'name' => 'Not Interested',
                'color' => '64748b',  // Slate
                'user_id' => $userId,
            ],

            [
                'name' => 'Customer',
                'color' => '059669',  // Emerald
                'user_id' => $userId,
            ],

            [
                'name' => 'Inactive',
                'color' => '71717a',  // Zinc
                'user_id' => $userId,
            ],

            [
                'name' => 'Pending',
                'color' => 'eab308',  // Yellow-500
                'user_id' => $userId,
            ],
            [
                'name' => 'Junk',
                'color' => 'eab308',  // Yellow-500
                'user_id' => $userId,
            ],
        ];

        foreach ($statuses as $status) {
            Status::query()->create($status);
        }
    }
}
