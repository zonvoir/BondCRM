<?php

namespace App\Http\Requests\Setup;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MicrosoftSocialiteRequest extends FormRequest
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
        ];
    }
}
