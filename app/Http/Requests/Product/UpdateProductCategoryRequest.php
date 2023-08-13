<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateProductCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {

        return Gate::allows('update_product_category');

    }

    public function rules(): array
    {

        $product_cat_id = $this->route('id');

        return [
            'name' => ['required', 'string', Rule::unique('product_categories')->ignore($product_cat_id)],
            'slug' => ['required', 'string', Rule::unique('product_categories')->ignore($product_cat_id)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => str()->slug($this->slug),
        ]);
    }
}
