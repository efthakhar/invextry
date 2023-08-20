<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update_account');
    }

    public function rules(): array
    {
        $account_id = $this->route('id');

        return [
            'name' => ['required', 'string', Rule::unique('accounts')->ignore($account_id)],
            'bank_name' => ['string', 'required_with:branch_name'],
            'branch_name' => ['string', 'nullable'],
            'account_number' => ['required', 'string'],
            'details' => ['nullable', 'string'],
            'status' => ['required', 'in:active,disabled'],
        ];
    }
}
