<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreatePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create_purchase');
    }

    public function rules(): array
    {
        return [

            'invoice_date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
            'warehouse_id' => ['required'],
            'party_id' => ['required'],
            'shipping_cost' => ['nullable', 'numeric'],
            'discount_type' => ['nullable', 'in:flat,percentage'],
            'discount' => ['nullable', 'numeric'],
            'invoice_tax_rate' => ['nullable', 'numeric'],
            'paid_amount' => ['nullable', 'numeric'],
            'items' => [
                function ($attribute, $value, $fail) {
                    if (! is_array($value)) {
                        $fail($attribute.' must be an array.');
                    }
                },
                function ($attribute, $value, $fail) {
                    if (count($value) < 1) {
                        $fail('select at least one item');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'invoice_date' => 'The purchase date is required',
            'party_id.required' => 'The supplier field is required',
            'warehouse_id.required' => 'The warehouse field is required',
        ];
    }
}
