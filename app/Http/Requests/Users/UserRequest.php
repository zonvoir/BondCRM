<?php

namespace App\Http\Requests\Users;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use LaravelCaseMapperRequest\Attributes\MapName;
use LaravelCaseMapperRequest\Mappers\SnakeCaseMapper;
use LaravelCaseMapperRequest\Traits\HasKeyTransformers;

#[MapName(SnakeCaseMapper::class)]
class UserRequest extends FormRequest
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
            'roles' => ['required', 'array', 'min:1'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'disposable_email'],
            'password' => ['nullable', 'confirmed', 'min:8', 'max:255'],
            'password_confirmation' => ['nullable', 'required_with:password', 'min:8', 'max:255'],
        ];
    }
}
