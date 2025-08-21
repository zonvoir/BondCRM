<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GeneralSettingsRequest extends FormRequest
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
            'footerText' => 'required|string|max:100',
            'iconLogoDark' => 'nullable|image|mimes:png,svg|dimensions:min_width=512,min_height=512|max:1024',
            'iconLogoWhite' => 'nullable|image|mimes:png,svg|dimensions:min_width=512,min_height=512|max:1024',
            'logoDark' => 'nullable|image|mimes:png,svg|dimensions:min_width=300,min_height=55|max:1024',
            'logoWhite' => 'nullable|image|mimes:png,svg|dimensions:min_width=300,min_height=55|max:1024',
            'favicon' => 'nullable|image|mimes:png,ico|dimensions:min_width=50,min_height=50|max:1024',
            'countries' => 'required|array',
            'timezones' => 'required|array',
        ];
    }
}
