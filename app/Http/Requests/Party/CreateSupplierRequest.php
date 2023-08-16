<?php

namespace App\Http\Requests\Party;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CreateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create_customer') && Gate::allows('create_supplier');
    }

    public function rules(): array
    {
        return [

            'name' => ['required', 'string', Rule::unique('parties')],
            'phone' => ['required', 'string', Rule::unique('parties')],
            'status' => ['required', 'in:active,disabled'],

            'email' => ['nullable', 'string', Rule::unique('parties')],
            'tax_number' => ['nullable', 'string', Rule::unique('parties')],

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
