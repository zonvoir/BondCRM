<?php

namespace App\Http\Requests\Setup;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use LaravelCaseMapperRequest\Attributes\MapName;
use LaravelCaseMapperRequest\Mappers\SnakeCaseMapper;
use LaravelCaseMapperRequest\Traits\HasKeyTransformers;

#[MapName(SnakeCaseMapper::class)]
class GeneralSettingsRequest extends FormRequest
{
    use HasKeyTransformers;

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
            'icon_logo_dark' => 'nullable|image|mimes:png,svg|dimensions:min_width=512,min_height=512|max:1024',
            'icon_logo_white' => 'nullable|image|mimes:png,svg|dimensions:min_width=512,min_height=512|max:1024',
            'logo_dark' => 'nullable|image|mimes:png,svg|dimensions:min_width=300,min_height=55|max:1024',
            'logo_white' => 'nullable|image|mimes:png,svg|dimensions:min_width=300,min_height=55|max:1024',
            'favicon' => 'nullable|image|mimes:png,ico|dimensions:min_width=50,min_height=50|max:1024',
            'company_name' => 'required|string',
            'allowed_file_types' => 'required|string',
        ];
    }
}
