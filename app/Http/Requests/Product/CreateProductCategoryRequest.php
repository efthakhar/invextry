<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CreateProductCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create_product_category');
    }

    public function rules(): array
    {

        return [
            'name' => ['required', 'string', Rule::unique('product_categories')],
            'slug' => ['required', 'string', Rule::unique('product_categories')],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => str()->slug($this->slug),
        ]);
    }
}
