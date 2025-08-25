<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use LaravelCaseMapperRequest\Attributes\MapName;
use LaravelCaseMapperRequest\Mappers\SnakeCaseMapper;
use LaravelCaseMapperRequest\Traits\HasKeyTransformers;

#[MapName(SnakeCaseMapper::class)]
class ImapRequest extends FormRequest
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
            'imap_server' => 'required|string|max:255',
            'imap_user_name' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'imap_port' => 'required|string|max:255',
            'folder' => 'required|string|max:255',
            'imap_encryption' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ];
    }
}
