<?php

namespace App\Http\Requests\Setup;

use App\Models\Setup\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StatusRequest extends FormRequest
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
            'id' => ['nullable'],
            'name' => ['required', Rule::unique(Status::class)->ignore(request('id'))],
            'color' => 'nullable',
        ];
    }
}
