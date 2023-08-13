<?php

namespace App\Http\Requests\Unit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CreateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create_unit');
    }

    public function rules(): array
    {

        return [
            'name' => 'required|max:30|unique:units',
            'short_name' => 'required|max:10|unique:units',
            'base_unit_id' => ['nullable', 'numeric', 'required_with_all:operator,operation_value'],
            'operator' => ['nullable', Rule::in(['divide', 'multiply']), 'required_with:base_unit_id'],
            'operation_value' => ['nullable', 'numeric', 'required_with:base_unit_id'],
        ];
    }
}
