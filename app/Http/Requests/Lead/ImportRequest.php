<?php

namespace App\Http\Requests\Lead;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => 'required|mimes:xlsx,csv',
            'status' => 'nullable',
            'source' => 'nullable',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
