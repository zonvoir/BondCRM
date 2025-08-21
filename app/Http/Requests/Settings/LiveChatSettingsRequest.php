<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LiveChatSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required'],
            'pusher_app_id' => ['nullable', 'string'],
            'pusher_app_key' => ['nullable', 'string'],
            'pusher_app_secret' => ['nullable', 'string'],
            'pusher_host' => ['nullable', 'string'],
            'pusher_port' => ['nullable', 'string'],
            'pusher_scheme' => ['nullable', 'string'],
            'pusher_app_cluster' => ['nullable', 'string'],
            'ably_key' => ['nullable', 'string'],
        ];
    }
}
