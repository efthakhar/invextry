<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreatePaymentRequest extends FormRequest
{
    protected $selected_account_balance;

    public function __construct()
    {

    }

    public function authorize(): bool
    {
        return Gate::allows('create_payment');
    }

    public function rules(): array
    {

        return [
            'invoice_id' => ['required'],
            'account_id' => ['required'],
            'payment_method' => ['required'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'note' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'invoice_id.required' => 'The invoice field is required',
            'account_id.required' => 'The account field is required',
            'payment_method.required' => 'The payment method field is required',
        ];
    }
}
