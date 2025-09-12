<?php

use App\Models\Setup\GeneralSettings;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

if (! function_exists('timezones')) {
    function timezones(): array
    {
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        return array_map(function ($timezone) {
            return [
                'name' => $timezone,
                'code' => $timezone,
            ];
        }, $timezones);
    }

}

if (! function_exists('dateFormats')) {
    function dateFormats(): array
    {
        return [
            // Date only
            ['code' => 'd-m-Y', 'name' => 'DD-MM-YYYY'],       // 05-09-2025
            ['code' => 'd/m/Y', 'name' => 'DD/MM/YYYY'],       // 05/09/2025
            ['code' => 'd.m.Y', 'name' => 'DD.MM.YYYY'],       // 05.09.2025
            ['code' => 'm-d-Y', 'name' => 'MM-DD-YYYY'],       // 09-05-2025
            ['code' => 'm.d.Y', 'name' => 'MM.DD.YYYY'],       // 09.05.2025
            ['code' => 'm/d/Y', 'name' => 'MM/DD/YYYY'],       // 09/05/2025
            ['code' => 'Y-m-d', 'name' => 'YYYY-MM-DD'],       // 2025-09-05
            ['code' => 'Y/m/d', 'name' => 'YYYY/MM/DD'],       // 2025/09/05
            ['code' => 'Y.m.d', 'name' => 'YYYY.MM.DD'],       // 2025.09.05
            ['code' => 'M d, Y', 'name' => 'Mon DD, YYYY'],    // Sep 05, 2025
            ['code' => 'd M Y', 'name' => 'DD Mon YYYY'],      // 05 Sep 2025
            ['code' => 'F d, Y', 'name' => 'Month DD, YYYY'],  // September 05, 2025
            ['code' => 'D, d M Y', 'name' => 'Day, DD Mon YYYY'], // Fri, 05 Sep 2025
            ['code' => 'l, d F Y', 'name' => 'Weekday, DD Month YYYY'], // Friday, 05 September 2025
            ['code' => 'd/m/y', 'name' => 'DD/MM/YY'],         // 05/09/25
            ['code' => 'm/d/y', 'name' => 'MM/DD/YY'],         // 09/05/25
            ['code' => 'd-m-y', 'name' => 'DD-MM-YY'],         // 05-09-25
            ['code' => 'jS M Y', 'name' => 'Ordinal Mon YYYY'], // 5th Sep 2025
            ['code' => 'jS F Y', 'name' => 'Ordinal Month YYYY'], // 5th September 2025
            ['code' => 'D M j, Y', 'name' => 'Day Mon D, YYYY'],  // Fri Sep 5, 2025

            // Date + Time (24-hour)
            ['code' => 'Y-m-d H:i', 'name' => 'YYYY-MM-DD HH:MM'],           // 2025-09-05 14:30
            ['code' => 'd-m-Y H:i', 'name' => 'DD-MM-YYYY HH:MM'],           // 05-09-2025 14:30
            ['code' => 'd/m/Y H:i', 'name' => 'DD/MM/YYYY HH:MM'],           // 05/09/2025 14:30
            ['code' => 'd.m.Y H:i', 'name' => 'DD.MM.YYYY HH:MM'],           // 05.09.2025 14:30

            // Date + Time (12-hour)
            ['code' => 'Y-m-d h:i A', 'name' => 'YYYY-MM-DD hh:MM AM/PM'],   // 2025-09-05 02:30 PM
            ['code' => 'd-m-Y h:i A', 'name' => 'DD-MM-YYYY hh:MM AM/PM'],   // 05-09-2025 02:30 PM
            ['code' => 'd/m/Y h:i A', 'name' => 'DD/MM/YYYY hh:MM AM/PM'],   // 05/09/2025 02:30 PM
            ['code' => 'd.m.Y h:i A', 'name' => 'DD.MM.YYYY hh:MM AM/PM'],   // 05.09.2025 02:30 PM
        ];
    }
}

if (! function_exists('timeFormat')) {
    function timeFormat(): array
    {
        return [
            ['code' => '24', 'name' => '24 hours'],
            ['code' => '12', 'name' => '12 hours'],
        ];
    }

}

if (! function_exists('logos')) {
    function logos()
    {
        if (Schema::hasTable('general_settings')) {
            $generalSettings = GeneralSettings::select('icon_logo_dark', 'icon_logo_white', 'logo_dark', 'logo_white', 'favicon')->first();

            return [
                'icon_logo_dark' => Storage::disk('public')->url('logo/'.$generalSettings->icon_logo_dark),
                'icon_logo_white' => Storage::disk('public')->url('logo/'.$generalSettings->icon_logo_white),
                'logo_dark' => Storage::disk('public')->url('logo/'.$generalSettings->logo_dark),
                'logo_white' => Storage::disk('public')->url('logo/'.$generalSettings->logo_white),
                'favicon' => Storage::disk('public')->url('logo/'.$generalSettings->favicon),
            ];
        }

        return [
            'favicon' => assert('favicon.png'),
        ];

    }
}

if (! function_exists('getFirstAndLastWord')) {
    function getFirstAndLastWord(string $name): string
    {
        $name = mb_trim($name);

        if ($name === '') {
            return '';
        }

        $words = preg_split('/\s+/', $name, flags: PREG_SPLIT_NO_EMPTY);

        return match (count($words)) {
            0 => '',
            1 => mb_strtoupper(mb_substr($words[0], 0, 1)),
            default => mb_strtoupper(
                mb_substr($words[0], 0, 1).mb_substr($words[array_key_last($words)], 0, 1)
            ),
        };
    }
}

if (! function_exists('humanTime')) {
    function humanTime(?string $timestamp): string
    {
        if (empty($timestamp)) {
            return '';
        }

        $dt = Carbon::parse($timestamp)->setTimezone(config('app.timezone'));

        return $dt->greaterThanOrEqualTo(now()->subHours(12))
            ? $dt->format('h:i A')    // e.g., 03:45 PM
            : $dt->diffForHumans();   // e.g., 2 days ago
    }
}
