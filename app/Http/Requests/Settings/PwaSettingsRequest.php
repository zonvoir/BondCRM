<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PwaSettingsRequest extends FormRequest
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
            'appName' => ['required', 'string'],
            'description' => ['required', 'string'],
            'appLogo' => 'nullable|image|mimes:png|dimensions:min_width=512,min_height=512|max:1024',
            'themeColor' => 'required',
        ];
    }
}
