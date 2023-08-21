<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update_account_adjustment');
    }

    public function rules(): array
    {

        return [
            'account_id' => ['required'],
            'amount' => ['numeric', 'required'],
            'type' => ['required', 'in:add,subtract'],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'account_id.required' => 'The Account field is required',
        ];
    }
}
