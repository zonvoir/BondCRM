<?php

namespace App\Http\Requests\Black;

use App\Models\Black\BlackListKeyword;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlackListKeywordRequest extends FormRequest
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
            'keyword' => ['required', Rule::unique(BlackListKeyword::class)->ignore(request('id'))],
        ];
    }
}
