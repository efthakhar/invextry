<?php

namespace Database\Seeders;

use App\Models\Product\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public $product_cats = [
        ['id' => 1, 'name' => 'beg', 'slug' => 'beg', 'category_img' => 21],
        ['id' => 2, 'name' => 'camera', 'slug' => 'camera', 'category_img' => 22],
        ['id' => 3, 'name' => 'chocolate', 'slug' => 'chocolate', 'category_img' => 23],
        ['id' => 4, 'name' => 'drinks', 'slug' => 'drinks', 'category_img' => 24],
        ['id' => 5, 'name' => 'fruits', 'slug' => 'fruits', 'category_img' => 25],
        ['id' => 6, 'name' => 'furniture', 'slug' => 'furniture', 'category_img' => 26],
        ['id' => 7, 'name' => 'glass', 'slug' => 'glass', 'category_img' => 27],
        ['id' => 8, 'name' => 'headphone', 'slug' => 'headphone', 'category_img' => 28],
        ['id' => 9, 'name' => 'laptop', 'slug' => 'laptop', 'category_img' => 29],
        ['id' => 10, 'name' => 'milk powder', 'slug' => 'milk-powder', 'category_img' => 30],
        ['id' => 11, 'name' => 'oil', 'slug' => 'oil', 'category_img' => 31],
        ['id' => 12, 'name' => 'shoe', 'slug' => 'shoe', 'category_img' => 32],
        ['id' => 13, 'name' => 'smart phone', 'slug' => 'smart-phone', 'category_img' => 33],
        ['id' => 14, 'name' => 't shirt', 'slug' => 't-shirt', 'category_img' => 34],
        ['id' => 15, 'name' => 'tablet', 'slug' => 'tablet', 'category_img' => 35],
        ['id' => 16, 'name' => 'toy', 'slug' => 'toy', 'category_img' => 36],
        ['id' => 17, 'name' => 'tv', 'slug' => 'tv', 'category_img' => 37],
        ['id' => 18, 'name' => 'watch', 'slug' => 'watch', 'category_img' => 38],

    ];

    public function run(): void
    {
        foreach ($this->product_cats as $product_cat) {
            ProductCategory::create([
                'name' => $product_cat['name'],
                'slug' => $product_cat['slug'],
            ])->attachMedia($product_cat['category_img'], 'thumbnail');
        }
    }
}
