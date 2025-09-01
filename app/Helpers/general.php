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
            ['code' => 'd-m-Y', 'name' => 'd-m-Y'],
            ['code' => 'd/m/Y', 'name' => 'd/m/Y'],
            ['code' => 'm-d-Y', 'name' => 'm-d-Y'],
            ['code' => 'm.d.Y', 'name' => 'm.d.Y'],
            ['code' => 'm/d/Y', 'name' => 'm/d/Y'],
            ['code' => 'Y-m-d', 'name' => 'Y-m-d'],
            ['code' => 'd.m.Y', 'name' => 'd.m.Y'],
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
