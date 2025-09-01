<?php

namespace App\Services\Mails;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class MailService
{
    public function pagination($data, $count = null): LengthAwarePaginator
    {
        $page = request('page', 1);
        $perPage = 10;

        if (empty($count)) {
            $count = $data->count();
        }

        return new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $count,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function gmailPagination($response): LengthAwarePaginator
    {
        $page = request('page', 1);
        $perPage = 10;

        $items = collect($response['data'] ?? []);

        return new LengthAwarePaginator(
            $items,
            $response['total'] ?? 0,
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );
    }

    public function outlookPagination($response): LengthAwarePaginator
    {
        $page = request('page', 1);
        $perPage = 10;

        $messages = collect($response['value'] ?? [])->map(function ($message) {
            if (isset($message['receivedDateTime'])) {
                $message['receivedDateTime'] = humanTime($message['receivedDateTime']);
            }

            return $message;
        });

        return new LengthAwarePaginator(
            $messages,
            $response['@odata.count'] ?? count($messages),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );

    }
}
