<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update_warehouse');
    }

    public function rules(): array
    {
        $warehouse_id = $this->route('id');

        return [
            'name' => ['required', 'string', Rule::unique('warehouses')->ignore($warehouse_id)],
            'email' => ['required', 'email', Rule::unique('warehouses')->ignore($warehouse_id)],
            'phone' => ['required', 'string', Rule::unique('warehouses')->ignore($warehouse_id)],
            'address' => ['nullable', 'string'],
        ];
    }
}
