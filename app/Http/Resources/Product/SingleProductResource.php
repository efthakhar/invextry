<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $gallery = [];

        if ($this->getMedia('gallery')) {
            foreach ($this->getMedia('gallery') as $m) {
                $gallery[] = [
                    'id' => $m->id,
                    'url' => $m->getUrl(),
                ];
            }
        }

        return [

            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'slug' => $this->slug,
            'product_type' => $this->product_type,
            'barcode_symbology' => $this->barcode_symbology,
            'stock_alert_quantity' => $this->stock_alert_quantity,
            'stock_quantity' => $this->stock_quantity,
            'purchase_price' => $this->purchase_price,
            'sale_price' => $this->sale_price,
            'parent_id' => $this->parent_id,
            'brand_id' => $this->brand_id,
            'brand' => $this->brand,
            'category_id' => $this->category_id,
            'category' => $this->category,
            'unit_id' => $this->unit_id,
            'unit' => $this->unit,
            'purchase_unit_id' => $this->purchase_unit_id,
            'purchase_unit' => $this->purchase_unit,
            'sale_unit_id' => $this->sale_unit_id,
            'sale_unit' => $this->sale_unit,
            'tax_id' => $this->tax_id,
            'tax' => $this->tax,
            'tax_type' => $this->tax_type,
            'description' => $this->description,

            'gallery' => $gallery,
        ];
    }
}
