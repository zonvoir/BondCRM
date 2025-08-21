<?php

namespace App\Http\Resources\Settings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiveChatSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type,
            'pusher_app_id' => $this->pusher_app_id,
            'pusher_app_key' => $this->pusher_app_key,
            'pusher_app_secret' => $this->pusher_app_secret,
            'pusher_host' => $this->pusher_host,
            'pusher_port' => $this->pusher_port,
            'pusher_scheme' => $this->pusher_scheme,
            'pusher_app_cluster' => $this->pusher_app_cluster,
            'ably_key' => $this->ably_key,
        ];
    }
}
