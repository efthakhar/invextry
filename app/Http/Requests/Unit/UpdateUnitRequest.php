<?php

namespace App\Http\Requests\Unit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update_unit');
    }

    public function rules(): array
    {
        $unit_id = $this->route('id');

        return [
            'name' => ['required', 'max:30', Rule::unique('units')->ignore($unit_id)],
            'short_name' => ['required', 'max:10', Rule::unique('units')->ignore($unit_id)],
            'base_unit_id' => ['nullable', 'numeric', 'required_with_all:operator,operation_value'],
            'operator' => ['nullable', Rule::in(['divide', 'multiply']), 'required_with:base_unit_id'],
            'operation_value' => ['nullable', 'numeric', 'required_with:base_unit_id'],
        ];
    }
}
