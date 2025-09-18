<?php

namespace App\Http\Requests\Lead;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LaravelCaseMapperRequest\Attributes\MapName;
use LaravelCaseMapperRequest\Mappers\SnakeCaseMapper;
use LaravelCaseMapperRequest\Traits\HasKeyTransformers;

#[MapName(SnakeCaseMapper::class)]
class SocialSyncRequest extends FormRequest
{
    use HasKeyTransformers;

    public function rules(): array
    {
        return [
            'provider' => ['required', 'array'],
            'provider.name' => ['required', 'string', 'max:255'],
            'provider.code' => ['required', 'integer'],

            'algorithm' => ['required', 'array'],
            'algorithm.name' => ['required', 'string', 'max:255'],
            'algorithm.code' => ['required', 'string', Rule::in(['default', 'custom', 'chatgpt'])],

            'start_date' => ['nullable', 'date', 'before_or_equal:end_date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
