<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class StockService
{
    public function __construct()
    {
    }

    public function addStock($warehouse_id, $product_id, $product_quantity)
    {
        if (DB::table('product_stocks')->where(['product_id' => $product_id, 'warehouse_id' => $warehouse_id])->count() > 0) {
            DB::table('product_stocks')->where(['product_id' => $product_id, 'warehouse_id' => $warehouse_id])->increment('stock_quantity', $product_quantity);
            DB::table('products')->where('id', $product_id)->increment('stock_quantity', $product_quantity);
        } else {
            DB::table('product_stocks')->insert(['product_id' => $product_id,'warehouse_id' => $warehouse_id,'stock_quantity' => $product_quantity, ]);
            DB::table('products')->where('id', $product_id)->increment('stock_quantity', $product_quantity);
        }
    }
}
