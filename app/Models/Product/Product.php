<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class Product extends Model
{
    use HasFactory;
    use Mediable;

    protected $fillable = [
        'code',
        'name',
        'slug',
        'product_type',
        'barcode_symbology',
        'stock_quantity',
        'stock_alert_quantity',
        'purchase_price',
        'sale_price',
        'parent_id',
        'brand_id',
        'category_id',
        'unit_id',
        'purchase_unit_id',
        'sale_unit_id',
        'tax_id',
        'tax_type',
        'description',
    ];

    protected $attributes = [
        'product_type' => 'single', // variable/digital/single
        'barcode_symbology' => 'CODE128', // CODE128,CODE39,EAN8,EAN13,UPC
        'tax_type' => 'exclusive',
        'stock_quantity' => 0,
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function purchase_unit()
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id');
    }

    public function sale_unit()
    {
        return $this->belongsTo(Unit::class, 'sale_unit_id');
    }

    public function tax()
    {
        return $this->belongsTo(\App\Models\Setting\Tax::class, 'tax_id');
    }
}
