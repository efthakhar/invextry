<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CreateWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create_warehouse');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', Rule::unique('warehouses')],
            'email' => ['required', 'email', Rule::unique('warehouses')],
            'phone' => ['required', 'string', Rule::unique('warehouses')],
            'address' => ['nullable', 'string'],
        ];
    }
}
