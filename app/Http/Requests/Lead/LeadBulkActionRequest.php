<?php

namespace App\Http\Requests\Lead;

use Illuminate\Foundation\Http\FormRequest;
use LaravelCaseMapperRequest\Attributes\MapName;
use LaravelCaseMapperRequest\Mappers\SnakeCaseMapper;
use LaravelCaseMapperRequest\Traits\HasKeyTransformers;

#[MapName(SnakeCaseMapper::class)]
class LeadBulkActionRequest extends FormRequest
{
    use HasKeyTransformers;

    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'distinct', 'exists:leads,id'],
            'is_delete' => ['nullable', 'boolean'],
            'mark_lost' => ['nullable', 'boolean'],
            'status' => ['nullable', 'array'],
            'source' => ['nullable', 'array'],
            'last_contact' => ['nullable', 'date'],
            'type' => ['nullable', 'in:public,private'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'ids.required' => 'At least one lead id is required.',
            'ids.*.exists' => 'One or more lead ids are invalid.',
            'status.required' => 'At least one lead id is required.',
            'source.required' => 'At least one lead id is required.',
        ];
    }
}
