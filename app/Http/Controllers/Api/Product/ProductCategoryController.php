<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductCategoryRequest;
use App\Http\Requests\Product\UpdateProductCategoryRequest;
use App\Http\Resources\Product\ProductCategoryCollection;
use App\Http\Resources\Product\ProductCategoryResource;
use App\Models\Product\ProductCategory;
use Exception;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_product_category');

        $page = $request->query('page');
        $per_page = $request->query('per_page', 10);
        $q_name = $request->query('q_name');

        $product_categories = ProductCategory::query()->withMedia(['thumbnail']);

        $product_categories->when($q_name, function ($query, $q_name) {
            $query->where('name', 'LIKE', '%'.$q_name.'%');
        });

        $product_categories = $page ? $product_categories->orderBy('id', 'desc')->paginate($per_page)
            : $product_categories->orderBy('id', 'desc')->get();

        return new ProductCategoryCollection($product_categories);
    }

    public function show($product_category_id)
    {
        $this->authorize('view_product_category');

        $product_category = ProductCategory::where('id', $product_category_id)->withMedia(['thumbnail'])->first();

        return new ProductCategoryResource($product_category);
    }

    public function store(CreateProductCategoryRequest $request)
    {
        try {
            ProductCategory::create($request->validated())
                ->attachMedia($request->thumbnail, 'thumbnail');
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to create product_category',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'product_category created succesfully',
        ], 201);
    }

    public function update(UpdateProductCategoryRequest $request, $product_category_id)
    {

        try {

            $product_category = ProductCategory::find($product_category_id);
            $product_category->update($request->validated());
            $product_category->detachMediaTags('thumbnail');
            $product_category->attachMedia($request->thumbnail, 'thumbnail');

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'failed to update product category',
            ], 500);

        }

        return response()->json([
            'status' => 'success',
            'message' => 'product_category updated succesfully',
        ], 200);
    }

    public function delete($ids)
    {
        $this->authorize('delete_product_category');

        $ids = explode(',', $ids);

        try {
            ProductCategory::destroy($ids);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to delete product_category',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'product_category deleted succesfully',
        ], 204);
    }
}
