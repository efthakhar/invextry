<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CreateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create_account');
    }

    public function rules(): array
    {

        return [
            'name' => ['required', 'string', Rule::unique('accounts')],
            'bank_name' => ['string', 'required_with:branch_name'],
            'branch_name' => ['string', 'nullable'],
            'account_number' => ['required', 'string'],
            'balance' => ['numeric'],
            'details' => ['nullable', 'string'],
            'status' => ['required', 'in:active,disabled'],
        ];
    }
}
