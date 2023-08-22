<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CreateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create_product');
    }

    public function rules(): array
    {

        return [

            'code' => ['required', 'string', Rule::unique('products')],
            'name' => ['required', 'string', Rule::unique('products')],
            'slug' => ['required', 'string', Rule::unique('products')],

            'product_type' => ['required', 'string', Rule::in(['single', 'variable', 'digital'])],
            'barcode_symbology' => ['required', 'string', Rule::in(['CODE128', 'CODE39', 'EAN8', 'EAN13', 'UPC'])],

            'stock_alert_quantity' => ['nullable', 'numeric'],
            'purchase_price' => ['required', 'numeric'],
            'sale_price' => ['required', 'numeric'],

            'parent_id' => ['nullable', 'numeric'],

            'brand_id' => ['nullable', 'numeric'],

            'category_id' => ['required', 'numeric'],

            'unit_id' => ['required', 'numeric'],
            // 'purchase_unit_id' => ['required', 'numeric'],
            // 'sale_unit_id' => ['required', 'numeric'],

            'tax_id' => ['nullable', 'numeric'],
            'tax_type' => ['required', 'string', Rule::in(['inclusive', 'exclusive'])],

            'description' => ['nullable', 'string'],

        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => str()->slug($this->slug),
            // temporary configuration till our system is just for single type product
            'product_type' => 'single',
            'parent_id' => null,
        ]);
    }
}
