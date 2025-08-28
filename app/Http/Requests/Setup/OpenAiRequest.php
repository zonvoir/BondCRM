<?php

namespace App\Http\Requests\Setup;

use Illuminate\Foundation\Http\FormRequest;
use LaravelCaseMapperRequest\Attributes\MapName;
use LaravelCaseMapperRequest\Mappers\SnakeCaseMapper;
use LaravelCaseMapperRequest\Traits\HasKeyTransformers;

#[MapName(SnakeCaseMapper::class)]

class OpenAiRequest extends FormRequest
{
    use HasKeyTransformers;

    public function rules(): array
    {
        return [
            'assistant_name' => 'required|string',
            'assistant_id' => 'required|string',
            'api_key' => 'required|string',
            'prompt' => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
