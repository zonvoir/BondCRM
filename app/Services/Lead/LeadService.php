<?php

namespace App\Services\Lead;

use App\Enums\MailProviderEnum;
use App\Enums\ScanAlgorithmEnum;
use App\Models\Country;
use App\Models\Lead;
use App\Models\Setup\Sources;
use App\Models\Setup\Status;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

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

    public function getStatus(): array
    {
        return Status::query()->withCount('leads')
            ->get(['id', 'name', 'color'])
            ->transform(function ($status) {
                return [
                    'name' => ucfirst($status->name),
                    'code' => $status->id,
                    'color' => $status->color,
                    'leads_count' => $status->leads_count,
                ];
            })
            ->toArray();
    }

    public function getSource(): array
    {
        return Sources::all(['id', 'source'])
            ->transform(function ($source) {
                return [
                    'name' => ucfirst($source->source),
                    'code' => $source->id,
                ];
            })
            ->toArray();

    }

    public function geCountries(): array
    {
        return Country::all(['id', 'name'])
            ->transform(function ($source) {
                return [
                    'name' => ucfirst($source->name),
                    'code' => $source->id,
                ];
            })
            ->toArray();
    }

    public function leadUpdateOrCreate(array $data): Model|Lead
    {
        return Lead::query()->updateOrCreate(
            [
                'id' => $data['id'] ?? null,
            ],
            $this->leadMap($data)
        );
    }

    public function leadMap(array $data): array
    {
        return [
            'id' => $data['id'] ?? null,
            'name' => $data['name'] ?? null,
            'user_id' => $data['user_id'] ?? auth()->id(),
            'sources_id' => data_get($data['source'], 'code') ?? null,
            'status_id' => data_get($data['status'], 'code') ?? null,
            'address' => $data['address'] ?? null,
            'position' => $data['position'] ?? null,
            'city' => $data['city'] ?? null,
            'email' => $data['email'] ?? null,
            'state' => $data['state'] ?? null,
            'website' => $data['website'] ?? null,
            'country_id' => data_get($data['country'], 'code') ?? null,
            'phone' => $data['phone'] ?? null,
            'zip_code' => $data['zip_code'] ?? null,
            'lead_value' => $data['lead_value'] ?? null,
            'company' => $data['company'] ?? null,
            'description' => $data['description'] ?? null,
            'date_contacted' => $data['date_contacted'] ?? null,
            'public' => $data['public'] ?? null,
            'is_date_contacted' => $data['is_date_contacted'] ?? null,
        ];
    }

    public function importSimulate($data): array
    {
        $sheets = Excel::toArray([], $data['file']);
        if (empty($sheets)) {
            return [];
        }

        $serialized = array_map('serialize', $sheets[0]);
        $uniqueSerialized = array_unique($serialized);
        $new = array_values(array_map('unserialize', $uniqueSerialized));

        $headers = array_shift($new);

        foreach ($new as &$row) {
            $insertAt5 = data_get($data, 'status.name');
            $insertAt6 = data_get($data, 'source.name');

            if (! is_array($row)) {
                $row = (array) $row;
            }
            array_splice($row, 5, 0, [$insertAt5, $insertAt6]);
        }
        unset($row);

        array_splice($headers, 5, 0, ['NewColA', 'NewColB']);

        array_unshift($new, $headers);

        return array_slice($new, 1);
    }
}
