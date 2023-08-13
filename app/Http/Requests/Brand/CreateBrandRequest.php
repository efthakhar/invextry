<?php

namespace App\Http\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CreateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create_brand');
    }

    public function rules(): array
    {

        return [
            'name' => ['required', 'string', Rule::unique('brands')],
            'slug' => ['required', 'string', Rule::unique('brands')],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => str()->slug($this->slug),
        ]);
    }
}
