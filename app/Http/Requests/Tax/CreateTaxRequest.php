<?php

namespace App\Http\Requests\Tax;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CreateTaxRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create_tax');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', Rule::unique('taxes')],
            'rate' => ['required', 'numeric'],
        ];
    }
}
