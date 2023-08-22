<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update_product');
    }

    public function rules(): array
    {
        $product_id = $this->route('id');

        return [

            'code' => ['required', 'string', Rule::unique('products')->ignore($product_id)],
            'name' => ['required', 'string', Rule::unique('products')->ignore($product_id)],
            'slug' => ['required', 'string', Rule::unique('products')->ignore($product_id)],

            'barcode_symbology' => ['required', 'string', Rule::in(['CODE128', 'CODE39', 'EAN8', 'EAN13', 'UPC'])],

            'stock_alert_quantity' => ['nullable', 'numeric'],
            'purchase_price' => ['required', 'numeric'],
            'sale_price' => ['required', 'numeric'],

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
        // remove product_type and parent_id so that user can not update product type and
        // parent of a  product variation. This will help to preserve consistancy of records
        $this->offsetUnset('product_type');
        $this->offsetUnset('parent_id');

        $this->merge([
            'slug' => str()->slug($this->slug),
        ]);
    }
}
