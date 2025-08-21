<?php

namespace App\Http\Requests\Users;

use EragPermission\Models\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
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
            'id' => 'nullable|integer',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Role::class)->ignore(request('id')),
            ],
        ];
    }
}
