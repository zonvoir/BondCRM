<?php

namespace App\Http\Requests\Setup;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EmailSettingsRequest extends FormRequest
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
            'mailDriver' => 'required|string|max:255',
            'mailHost' => 'required|string|max:255',
            'mailPort' => 'required|string|max:255',
            'mail' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:50',
            'mailEncryption' => 'required|string|max:255',
            'mailFromAddress' => 'required|string|max:500',
            'mailFromName' => 'required|string|max:255',
        ];
    }
}
