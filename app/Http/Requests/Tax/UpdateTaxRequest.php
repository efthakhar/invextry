<?php

namespace App\Http\Requests\Tax;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateTaxRequest extends FormRequest
{
    public function authorize(): bool
    {

        return Gate::allows('update_tax');

    }

    public function rules(): array
    {

        $tax_id = $this->route('id');

        return [
            'name' => ['required', 'string', Rule::unique('taxes')->ignore($tax_id)],
            'rate' => ['required', 'numeric'],
        ];
    }
}
