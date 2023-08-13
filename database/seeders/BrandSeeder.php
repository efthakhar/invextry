<?php

namespace Database\Seeders;

use App\Models\Product\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public $brands = [
        ['id' => 1, 'name' => 'walton', 'slug' => 'walton', 'logo' => 1],
        ['id' => 2, 'name' => 'bata', 'slug' => 'bata', 'logo' => 2],
        ['id' => 3, 'name' => 'pran', 'slug' => 'pran', 'logo' => 3],
        ['id' => 4, 'name' => 'regal', 'slug' => 'regal', 'logo' => 4],
        ['id' => 5, 'name' => 'arong', 'slug' => 'arong', 'logo' => 5],
        ['id' => 6, 'name' => 'philip', 'slug' => 'philip', 'logo' => 6],
        ['id' => 7, 'name' => 'mi', 'slug' => 'mi', 'logo' => 7],
        ['id' => 8, 'name' => 'panda', 'slug' => 'panda', 'logo' => 8],
        ['id' => 9, 'name' => 'samsung', 'slug' => 'samsung', 'logo' => 9],
        ['id' => 10, 'name' => 'vision', 'slug' => 'vision', 'logo' => 10],
        ['id' => 11, 'name' => 'hp', 'slug' => 'hp', 'logo' => 11],
        ['id' => 12, 'name' => 'fresh', 'slug' => 'fresh', 'logo' => 12],
        ['id' => 13, 'name' => 'nido', 'slug' => 'nido', 'logo' => 13],
        ['id' => 14, 'name' => 'pepsodent', 'slug' => 'pepsodent', 'logo' => 14],
        ['id' => 15, 'name' => 'rfl', 'slug' => 'rfl', 'logo' => 15],
        ['id' => 16, 'name' => 'dell', 'slug' => 'dell', 'logo' => 16],
        ['id' => 17, 'name' => 'asus', 'slug' => 'asus', 'logo' => 17],
        ['id' => 18, 'name' => 'dano', 'slug' => 'dano', 'logo' => 18],
        ['id' => 19, 'name' => 'casio', 'slug' => 'casio', 'logo' => 19],
        ['id' => 20, 'name' => 'yamah', 'slug' => 'yamah', 'logo' => 20],
    ];

    public function run(): void
    {
        foreach ($this->brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => $brand['slug'],
            ])->attachMedia($brand['logo'], 'logo');
        }
    }
}
