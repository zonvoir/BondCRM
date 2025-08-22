<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SettingsSocialiteRequest extends FormRequest
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
            'microsoftClientId' => ['required', 'string'],
            'microsoftClientSecret' => ['required', 'string'],
            'microsoftRedirect' => ['required', 'url'],
            'statusMicrosoft' => ['required'],

            'googleClientId' => ['required', 'string'],
            'googleClientSecret' => ['required', 'string'],
            'googleRedirect' => ['required', 'url'],
            'statusGoogle' => ['required'],
        ];
    }
}
