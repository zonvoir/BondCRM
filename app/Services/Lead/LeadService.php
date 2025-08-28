<?php

namespace App\Services\Lead;

use App\Enums\MailProviderEnum;
use App\Enums\ScanAlgorithmEnum;

class LeadService
{
    public function mapProvider(): array
    {
        $mailProviderEnum = MailProviderEnum::values();

        return array_map(function ($mailProviderEnum) {
            return [
                'name' => ucfirst($mailProviderEnum),
                'code' => $mailProviderEnum,
            ];
        }, $mailProviderEnum);
    }

    public function mapScanAlgorithm(): array
    {
        $scanAlgorithm = ScanAlgorithmEnum::values();

        return array_map(function ($scanAlgorithm) {
            return [
                'name' => ucfirst($scanAlgorithm),
                'code' => $scanAlgorithm,
            ];
        }, $scanAlgorithm);
    }
}
