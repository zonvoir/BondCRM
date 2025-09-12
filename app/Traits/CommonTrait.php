<?php

namespace App\Traits;

use App\Models\Setup\GeneralSettings;

trait CommonTrait
{
    public function getDateFormatSettings(): string
    {
        $settings = GeneralSettings::query()->select('date_format')->first();

        return $settings?->date_format['code'] ?? 'Y-m-d';
    }
}
