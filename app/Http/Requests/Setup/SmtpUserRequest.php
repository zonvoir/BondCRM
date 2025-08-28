<?php

namespace App\Http\Requests\Setup;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use LaravelCaseMapperRequest\Attributes\MapName;
use LaravelCaseMapperRequest\Mappers\SnakeCaseMapper;
use LaravelCaseMapperRequest\Traits\HasKeyTransformers;

#[MapName(SnakeCaseMapper::class)]

class SmtpUserRequest extends FormRequest
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
            'mail_driver' => 'required|string|max:255',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|string|max:255',
            'mail' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:50',
            'mail_encryption' => 'required|string|max:255',
            'mail_from_address' => 'required|string|max:500',
            'mail_from_name' => 'required|string|max:255',
        ];
    }
}
