<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductListCollection;
use App\Http\Resources\Product\SingleProductResource;
use App\Models\Product\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_product');

        $page = $request->query('page') ?? 1;
        $per_page = $request->query('per_page') && $request->query('per_page') < 100 ? $request->query('per_page') : 10;
        $q_name = $request->query('q_name');

        $products = Product::query();

        $products->when($q_name, function ($query, $q_name) {
            $query->where('name', 'LIKE', '%'.$q_name.'%');
        });

        $products = $products->orderBy('id', 'desc')
            ->withMedia('gallery')
            ->with([
                'category:id,name',
                'brand:id,name',
                // 'tax:id,name',
                'unit:id,short_name',
                // 'sale_unit:id,name',
                // 'purchase_unit:id,name',
            ])
            ->paginate($per_page);

        return new ProductListCollection($products);
    }

    public function getProductsByNameAndWareHouse($warehouse_id, $name)
    {
        $this->authorize('view_product');

        $products = DB::table('products')
            ->leftJoin('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->leftJoin('taxes', 'products.tax_id', '=', 'taxes.id')
            ->join('units', 'products.sale_unit_id', '=', 'units.id')
            ->select([
                'products.id',
                'products.name',
                'products.sale_price',
                'product_stocks.stock_quantity',
                'taxes.rate as tax_rate',
                'products.tax_id',
                'products.tax_type',
                'products.sale_unit_id',
                'units.short_name',
            ])
            ->where('products.name', 'LIKE', '%'.$name.'%')
            ->where('product_stocks.warehouse_id', $warehouse_id)
            ->where('product_stocks.stock_quantity', '>', 0)
            ->limit(20)
            ->get();

        return $products;
    }

    public function getProductsByName($name)
    {
        $this->authorize('view_product');

        $products = DB::table('products')
            ->leftJoin('taxes', 'products.tax_id', '=', 'taxes.id')
            ->join('units', 'products.purchase_unit_id', '=', 'units.id')
            ->select([
                'products.id',
                'products.name',
                'products.purchase_price',
                'taxes.rate as tax_rate',
                'products.tax_id',
                'products.tax_type',
                'products.purchase_unit_id',
                'units.short_name',
            ])
            ->where('products.name', 'LIKE', '%'.$name.'%')
            ->limit(20)
            ->get();

        return $products;
    }

    public function show($product_id)
    {
        $this->authorize('view_product');

        $product = Product::where('id', $product_id)
            ->withMedia('gallery')->with([
                'category:id,name',
                'brand:id,name',
                'tax:id,name',
                'unit:id,short_name,name',
                'sale_unit:id,short_name,name',
                'purchase_unit:id,short_name,name',
            ])->first();

        return new SingleProductResource($product);
    }

    public function store(CreateProductRequest $request)
    {
        try {

            $product = new Product();

            $product->name = $request->validated('name');
            $product->slug = $request->validated('slug');
            $product->code = $request->validated('code');
            $product->product_type = 'single';
            $product->stock_alert_quantity = $request->validated('stock_alert_quantity');
            $product->purchase_price = $request->validated('purchase_price');
            $product->sale_price = $request->validated('sale_price');
            $product->parent_id = null;
            $product->brand_id = $request->validated('brand_id');
            $product->category_id = $request->validated('category_id');
            $product->unit_id = $request->validated('unit_id');
            $product->sale_unit_id = $request->validated('unit_id'); // $request->validated('sale_unit_id');
            $product->purchase_unit_id = $request->validated('unit_id'); // $request->validated('purchase_unit_id');
            $product->tax_id = $request->validated('tax_id');
            $product->tax_type = $request->validated('tax_type');
            $product->description = $request->validated('description');

            $product->save();

            $product->attachMedia($request->gallery, 'gallery');
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to create product'.$e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'product created succesfully',
        ], 201);
    }

    public function update(UpdateProductRequest $request, $product_id)
    {

        try {

            $product = Product::find($product_id);

            $product->name = $request->validated('name');
            $product->slug = $request->validated('slug');
            $product->code = $request->validated('code');
            $product->product_type = 'single';
            $product->stock_alert_quantity = $request->validated('stock_alert_quantity');
            $product->purchase_price = $request->validated('purchase_price');
            $product->sale_price = $request->validated('sale_price');
            $product->parent_id = null;
            $product->brand_id = $request->validated('brand_id');
            $product->category_id = $request->validated('category_id');
            $product->unit_id = $request->validated('unit_id');
            $product->sale_unit_id = $request->validated('unit_id'); // $request->validated('sale_unit_id');
            $product->purchase_unit_id = $request->validated('unit_id'); // $request->validated('purchase_unit_id');
            $product->tax_id = $request->validated('tax_id');
            $product->tax_type = $request->validated('tax_type');
            $product->description = $request->validated('description');

            $product->save();
            $product->detachMediaTags('gallery');
            $product->attachMedia($request->gallery, 'gallery');

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'failed to update product',
                'error' => $e->getMessage(),
            ], 500);

        }

        return response()->json([
            'status' => 'success',
            'message' => 'product updated succesfully',
        ], 200);
    }

    public function delete($ids)
    {
        $this->authorize('delete_product');

        $ids = explode(',', $ids);

        try {
            Product::destroy($ids);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to delete product',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'product deleted succesfully',
        ], 204);
    }
}
