<?php

namespace App\Http\Requests\Setup;

use Illuminate\Foundation\Http\FormRequest;
use LaravelCaseMapperRequest\Attributes\MapName;
use LaravelCaseMapperRequest\Mappers\SnakeCaseMapper;
use LaravelCaseMapperRequest\Traits\HasKeyTransformers;

#[MapName(SnakeCaseMapper::class)]
class LocalizationRequest extends FormRequest
{
    use HasKeyTransformers;

    public function rules(): array
    {
        return [
            'date_format' => 'required|array',
            'timezones' => 'required|array',
            'time_format' => 'required|array',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
