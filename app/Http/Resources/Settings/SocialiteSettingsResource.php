<?php

namespace App\Http\Resources\Settings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialiteSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'provider' => $this->provider ?? null,
            'client_id' => $this->client_id ?? null,
            'client_secret' => $this->client_secret ?? null,
            'redirect_url' => $this->redirect_url ?? null,
            'status' => $this->status ?? null,
        ];
    }
}
