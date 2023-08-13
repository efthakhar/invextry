<?php

namespace App\Http\Requests\Currency;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create_currency');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:30|unique:currencies',
            'code' => 'required|max:10|unique:currencies',
            'symbol' => 'required|max:5',
        ];
    }
}
