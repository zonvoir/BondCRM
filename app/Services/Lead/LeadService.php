<?php

namespace App\Services\Lead;

use App\Enums\ScanAlgorithmEnum;
use App\Models\Country;
use App\Models\Lead;
use App\Models\Setup\Sources;
use App\Models\Setup\Status;
use App\Models\Tag;
use App\Services\Mails\GmailService;
use App\Services\Mails\GraphMicrosoftService;
use App\Services\Mails\ImapFetcher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class LeadService
{
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
        $lead = Lead::query()->updateOrCreate(
            [
                'id' => $data['id'] ?? null,
            ],
            $this->leadMap($data)
        );

        if (! empty($data['tags'])) {
            $tagIds = collect($data['tags'])->map(function ($tagName) {
                return Tag::firstOrCreate(['name' => $tagName])->id;
            })->toArray();
            $lead->tags()->sync($tagIds);
        }

        return $lead;
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
            'public' => $data['public'] === 'public',
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

    public function bulkAction(array $data): array
    {
        $ids = $data['ids'] ?? [];
        if (empty($ids)) {
            return ['deleted' => 0, 'updated' => 0];
        }

        if (! empty($data['is_delete'])) {
            $deleted = Lead::query()->whereIn('id', $ids)->delete();

            return ['deleted' => $deleted, 'updated' => 0];
        }

        $updates = [];

        if (array_key_exists('mark_lost', $data) && $data['mark_lost']) {
            $lostStatusId = Status::query()->where('name', 'Lost')->firstOrFail()->id;
            $updates['status_id'] = $lostStatusId;
        }

        if (array_key_exists('status', $data) && ! empty($data['status'])) {
            $updates['status_id'] = data_get($data, 'status.code');
        }

        if (array_key_exists('source', $data) && ! empty($data['source']['code'])) {
            $updates['sources_id'] = data_get($data, 'source.code');
        }

        if (array_key_exists('type', $data) && ! empty($data['type'])) {
            $updates['public'] = $data['type'] === 'public';
        }

        if (array_key_exists('last_contact', $data) && ! empty($data['last_contact'])) {
            $iso = $data['last_contact'];
            $updates['date_contacted'] = is_null($iso)
                ? null
                : Carbon::parse($iso)->timezone(config('app.timezone'))->toDateTimeString();
        }

        if (! empty($updates)) {
            $updated = Lead::query()->whereIn('id', $ids)->update($updates);
        }

        if (! empty($data['tags'])) {
            $tagIds = collect($data['tags'])->map(function ($tagName) {
                return Tag::firstOrCreate(['name' => $tagName])->id;
            })->toArray();

            Lead::query()->whereIn('id', $ids)->get()->each(function ($lead) use ($tagIds) {
                $lead->tags()->sync($tagIds);
            });
        }

        return ['deleted' => 0, 'updated' => $updated ?? 0];
    }

    public function socialSync(array $data)
    {
        if (array_key_exists('provider', $data) && array_key_exists('algorithm', $data)) {
            $startDate = $data['start_date'] ?? null;
            $endDate = $data['end_date'] ?? null;
            $algorithm = data_get($data, 'algorithm.code');
            $provider = data_get($data, 'provider.name');
            $providerCode = data_get($data, 'provider.code');

            $auth = auth()->user();

            $type = Str::of($provider)->lower()->contains('webmail') ? 'webmail' : 'applemail';

            $service = match ($provider) {
                'Gmail' => new GmailService($auth),
                'Outlook' => new GraphMicrosoftService($auth),
                'Apple Mail', 'Webmail' => new ImapFetcher($auth),
            };

            $result = match ($provider) {
                'Gmail' => $service->getFolderMessages('INBOX', 10, '', '', '', $startDate, $endDate),
                'Outlook' => $service->getPaginatedInboxMessages(
                    top: 10,
                    skip: 0,
                    search: null,
                    folder: 'INBOX',
                ),
                'Apple Mail', 'Webmail' => $service->fetch('INBOX', $type),
                default => null,
            };

            $nextPageToken = $result['nextPageToken'] ?? null;
            if (! empty($result['@odata.nextLink'])) {
                $query = parse_url($result['@odata.nextLink'], PHP_URL_QUERY);
                parse_str($query, $parsed);
                $nextPageToken = $parsed['$skip'] ?? $parsed['$skiptoken'] ?? null;
            }

            return [
                'pageToken' => $nextPageToken,
            ];

        }

        return [];
    }
}
