<?php

namespace App\Http\Resources\Settings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'mailDriver' => $this->mail_driver ?? null,
            'mailHost' => $this->mail_host ?? null,
            'mailPort' => $this->mail_port ?? null,
            'mail' => $this->mail ?? null,
            'password' => $this->password ?? null,
            'mailEncryption' => $this->mail_encryption ?? null,
            'mailFromAddress' => $this->mail_from_address ?? null,
            'mailFromName' => $this->mail_from_name ?? null,
        ];
    }
}
