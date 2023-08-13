<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
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
            // 'code' => $this->code,
            'name' => $this->name,
            // 'slug' => $this->slug,
            // 'product_type' => $this->product_type,
            // 'barcode_symbology' => $this->barcode_symbology,
            'stock_quantity' => $this->stock_quantity,
            // 'stock_alert_quantity' => $this->stock_alert_quantity,
            'purchase_price' => $this->purchase_price,
            'sale_price' => $this->sale_price,
            // 'parent_id' => $this->parent_id,
            'brand' => $this->brand->name,
            'category' => $this->category->name,
            'unit' => $this->unit->short_name,
            // 'purchase_unit' => $this->purchase_unit,
            // 'sale_unit' => $this->sale_unit,
            // 'tax' => $this->tax,
            // 'tax_type' => $this->tax_type,
            // 'description' => $this->description,

            'gallery' => $gallery,
        ];
    }
}
