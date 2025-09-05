<?php

namespace App\Http\Resources\Settings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GeneralSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'icon_logo_dark' => $this->icon_logo_dark ? Storage::disk('public')->url('logo/'.$this->icon_logo_dark) : null,
            'icon_logo_white' => $this->icon_logo_white ? Storage::disk('public')->url('logo/'.$this->icon_logo_white) : null,
            'logo_dark' => $this->logo_dark ? Storage::disk('public')->url('logo/'.$this->logo_dark) : null,
            'logo_white' => $this->logo_white ? Storage::disk('public')->url('logo/'.$this->logo_white) : null,
            'favicon' => $this->favicon ? Storage::disk('public')->url('logo/'.$this->favicon) : null,
            'countries' => $this->countries,
            'company_name' => $this->company_name,
            'allowed_file_types' => $this->allowed_file_types,
            'date_format' => $this->date_format,
            'time_format' => $this->time_format,
            'timezones' => $this->timezones,
            'app_name' => $this->app_name,
            'app_description' => $this->app_description,
            'app_logo' => $this->app_logo ? asset($this->app_logo) : null,
            'theme_color' => $this->theme_color,
            'storage_link' => is_link(public_path('storage')),
        ];
    }
}
