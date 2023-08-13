<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Plank\Mediable\Facades\MediaUploader;

class DemoMediaSeeder extends Seeder
{
    public $media = [
        // Brand images
        ['id' => 1, 'path' => 'demo/brands/walton.jpg'],
        ['id' => 2, 'path' => 'demo/brands/bata.jpg'],
        ['id' => 3, 'path' => 'demo/brands/pran.jpg'],
        ['id' => 4, 'path' => 'demo/brands/regal.jpg'],
        ['id' => 5, 'path' => 'demo/brands/arong.jpg'],
        ['id' => 6, 'path' => 'demo/brands/philip.jpg'],
        ['id' => 7, 'path' => 'demo/brands/mi.jpg'],
        ['id' => 8, 'path' => 'demo/brands/panda.jpg'],
        ['id' => 9, 'path' => 'demo/brands/samsung.png'],
        ['id' => 10, 'path' => 'demo/brands/vision.jpg'],
        ['id' => 11, 'path' => 'demo/brands/hp.jpg'],
        ['id' => 12, 'path' => 'demo/brands/fresh.jpg'],
        ['id' => 13, 'path' => 'demo/brands/nido.jpg'],
        ['id' => 14, 'path' => 'demo/brands/pepsodent.jpg'],
        ['id' => 15, 'path' => 'demo/brands/rfl.jpg'],
        ['id' => 16, 'path' => 'demo/brands/dell.jpg'],
        ['id' => 17, 'path' => 'demo/brands/asus.jpg'],
        ['id' => 18, 'path' => 'demo/brands/dano.jpg'],
        ['id' => 19, 'path' => 'demo/brands/casio.jpg'],
        ['id' => 20, 'path' => 'demo/brands/yamah.jpg'],

        // Product Category images
        ['id' => 21, 'path' => 'demo/product-categories/beg.jpg'],
        ['id' => 22, 'path' => 'demo/product-categories/camera.jpg'],
        ['id' => 23, 'path' => 'demo/product-categories/chocolate.jpg'],
        ['id' => 24, 'path' => 'demo/product-categories/drinks.jpg'],
        ['id' => 25, 'path' => 'demo/product-categories/fruits.webp'],
        ['id' => 26, 'path' => 'demo/product-categories/furniture.jpg'],
        ['id' => 27, 'path' => 'demo/product-categories/glass.jpg'],
        ['id' => 28, 'path' => 'demo/product-categories/headphone.jpg'],
        ['id' => 29, 'path' => 'demo/product-categories/laptop.png'],
        ['id' => 30, 'path' => 'demo/product-categories/milk-powder.jpg'],
        ['id' => 31, 'path' => 'demo/product-categories/oil.png'],
        ['id' => 32, 'path' => 'demo/product-categories/shoe.jpg'],
        ['id' => 33, 'path' => 'demo/product-categories/smart-phone.jpg'],
        ['id' => 34, 'path' => 'demo/product-categories/t-shirt.jpg'],
        ['id' => 35, 'path' => 'demo/product-categories/tablet.jpg'],
        ['id' => 36, 'path' => 'demo/product-categories/toy.jpg'],
        ['id' => 37, 'path' => 'demo/product-categories/tv.jpg'],
        ['id' => 38, 'path' => 'demo/product-categories/watch.jpg'],

        // beg images
        ['id' => 39, 'path' => 'demo/products/step-out-beg.png'],
        ['id' => 40, 'path' => 'demo/products/hand-beg.jpg'],
        ['id' => 41, 'path' => 'demo/products/cloth-beg.jpg'],
        ['id' => 42, 'path' => 'demo/products/cloth-beg.jpg'],

        // shoe image
        ['id' => 43, 'path' => 'demo/products/leather-shoe.jpg'],
        ['id' => 44, 'path' => 'demo/products/loafer-shoe.jpg'],
        ['id' => 45, 'path' => 'demo/products/sneakers-shoe.jpg'],
        ['id' => 46, 'path' => 'demo/products/apex-slipper.jpg'],

        // smartphone images
        ['id' => 47, 'path' => 'demo/products/iphone.jpg'],
        ['id' => 48, 'path' => 'demo/products/infinix-phone.jpg'],
        ['id' => 49, 'path' => 'demo/products/vivo-phone.jpg'],

    ];

    public function run(): void
    {
        foreach ($this->media as $m) {
            MediaUploader::fromSource(storage_path($m['path']))
                ->toDestination('uploads', 'demo/'.date('Y-m-d'))
                ->upload();
        }
    }
}
