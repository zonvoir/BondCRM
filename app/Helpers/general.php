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
