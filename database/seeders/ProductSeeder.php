<?php

namespace Database\Seeders;

use App\Models\Product\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public $products = [
        [
            'id' => 1,
            'code' => '02381402314',
            'name' => 'Travel Beg',
            'slug' => 'man-travel-beg-black-and-white',
            'product_type' => 'simple',
            'barcode_symbology' => 'CODE128',
            'stock_quantity' => 0,
            'stock_alert_quantity' => null,
            'purchase_price' => 100,
            'sale_price' => 200,
            'parent_id' => null,
            'brand_id' => 2,
            'category_id' => 1,
            'unit_id' => 7,
            'purchase_unit_id' => 7,
            'sale_unit_id' => 7,
            'tax_id' => null,
            'tax_type' => 'exclusive',
            'description' => null,
            'gallery' => [39, 40, 41, 42],
        ],
        [
            'id' => 2,
            'code' => '0901802114',
            'name' => 'Casual Shoe for Man',
            'slug' => 'man-casueal-shoe',
            'product_type' => 'simple',
            'barcode_symbology' => 'CODE128',
            'stock_quantity' => 0,
            'stock_alert_quantity' => 25,
            'purchase_price' => 500,
            'sale_price' => 1000,
            'parent_id' => null,
            'brand_id' => 2,
            'category_id' => 12,
            'unit_id' => 8,
            'purchase_unit_id' => 8,
            'sale_unit_id' => 8,
            'tax_id' => 2,
            'tax_type' => 'exclusive',
            'description' => null,
            'gallery' => [44],
        ],
        [
            'id' => 3,
            'code' => '99080532114',
            'name' => 'Smart Phone Samsung A11 Black',
            'slug' => 'smart-phone-samsung-a11',
            'product_type' => 'simple',
            'barcode_symbology' => 'CODE128',
            'stock_quantity' => 0,
            'stock_alert_quantity' => 13,
            'purchase_price' => 10000,
            'sale_price' => 12000,
            'parent_id' => null,
            'brand_id' => 9,
            'category_id' => 13,
            'unit_id' => 8,
            'purchase_unit_id' => 8,
            'sale_unit_id' => 8,
            'tax_id' => 3,
            'tax_type' => 'inclusive',
            'description' => 'Latest Samsung Smart Phone.',
            'gallery' => [49, 47, 48],
        ],
    ];

    public function run(): void
    {
        foreach ($this->products as $product) {
            Product::create($product)->attachMedia($product['gallery'], 'gallery');
        }
    }
}
