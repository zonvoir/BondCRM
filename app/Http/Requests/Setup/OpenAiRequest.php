<?php

namespace App\Http\Requests\Setup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;
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
            'api_key' => ['required', 'string', function ($attribute, $value, $fail) {
                $response = Http::withToken($value)
                    ->get('https://api.openai.com/v1/models');

                if ($response->failed()) {
                    $fail('The provided API key is invalid or unauthorized.');
                }
            }],
            'prompt' => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
