<?php

namespace App\Http\Requests\Currency;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update_currency');
    }

    public function rules(): array
    {
        $currency_id = $this->route('id');

        return [
            'name' => ['required', 'max:30', Rule::unique('currencies')->ignore($currency_id)],
            'code' => ['required', 'max:10', Rule::unique('currencies')->ignore($currency_id)],
            'symbol' => ['required', 'max:5'],
        ];
    }
}
