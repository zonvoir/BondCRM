<?php

namespace App\Http\Resources\Lead;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'source' => $this->whenLoaded('source'),
            'status' => $this->whenLoaded('status'),
            'address' => $this->address,
            'position' => $this->position,
            'city' => $this->city,
            'email' => $this->email,
            'state' => $this->state,
            'website' => $this->website,
            'country' => $this->whenLoaded('country'),
            'phone' => $this->phone,
            'zip_code' => $this->zip_code,
            'lead_value' => $this->lead_value,
            'company' => $this->company,
            'description' => $this->description,
            'date_contacted' => $this->date_contacted ? Carbon::parse($this->date_contacted)->format('d-m-Y h:i A') : null,
            'public' => $this->public,
            'is_date_contacted' => $this->is_date_contacted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
