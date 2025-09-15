<?php

namespace App\Http\Requests\Lead;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use LaravelCaseMapperRequest\Attributes\MapName;
use LaravelCaseMapperRequest\Mappers\SnakeCaseMapper;
use LaravelCaseMapperRequest\Traits\HasKeyTransformers;

#[MapName(SnakeCaseMapper::class)]
class LeadRequest extends FormRequest
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
            'id' => 'nullable',
            'name' => 'required',
            'source' => 'required|array',
            'status' => 'required|array',
            'address' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'email' => 'email|disposable_email|max:255',
            'state' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'country' => 'nullable|array',
            'phone' => 'nullable|numeric|max:20',
            'zip_code' => 'nullable|numeric|max:20',
            'lead_value' => 'nullable|numeric',
            'company' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date_contacted' => 'nullable|string',
            'public' => ['nullable', 'in:public,private'],
            'is_date_contacted' => 'nullable|boolean',
        ];
    }
}
