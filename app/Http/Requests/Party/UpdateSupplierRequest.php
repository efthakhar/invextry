<?php

namespace App\Http\Requests\Party;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update_supplier');
    }

    public function rules(): array
    {
        $supplier_id = $this->route('id');

        return [

            'name' => ['required', 'string', Rule::unique('parties')->ignore($supplier_id)],
            'phone' => ['required', 'string', Rule::unique('parties')->ignore($supplier_id)],
            'status' => ['required', 'in:active,disabled'],

            'email' => ['nullable', 'string', Rule::unique('parties')->ignore($supplier_id)],
            'tax_number' => ['nullable', 'string', Rule::unique('parties')->ignore($supplier_id)],

            'company_name' => ['nullable', 'string'],
            'country' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'postal_code' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'billing_address' => ['nullable', 'string'],
            'shipping_address' => ['nullable', 'string'],

            'purchase_due' => ['nullable', 'numeric'],
            'purchase_return_due' => ['nullable', 'numeric'],

        ];
    }
}
