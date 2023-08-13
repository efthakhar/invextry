<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\CreateBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Http\Resources\Brand\BrandCollection;
use App\Http\Resources\Brand\BrandResource;
use App\Models\Product\Brand;
use Exception;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_brand');

        $page = $request->query('page');
        $per_page = $request->query('per_page', 10);
        $q_name = $request->query('q_name');

        $brands = Brand::query()->withMedia(['logo']);

        $brands->when($q_name, function ($query, $q_name) {
            $query->where('name', 'LIKE', '%'.$q_name.'%');
        });

        $brands = $page ? $brands->orderBy('id', 'desc')->paginate($per_page)
            : $brands->orderBy('id', 'desc')->get();

        return new BrandCollection($brands);
    }

    public function show($brand_id)
    {
        $this->authorize('view_brand');

        $brand = Brand::where('id', $brand_id)->withMedia(['logo'])->first();

        return new BrandResource($brand);
    }

    public function store(CreateBrandRequest $request)
    {
        try {
            Brand::create($request->validated())
                ->attachMedia($request->logo, 'logo');
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to create brand',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'brand created succesfully',
        ], 201);
    }

    public function update(UpdateBrandRequest $request, $brand_id)
    {

        try {

            $brand = Brand::find($brand_id);
            $brand->update($request->validated());
            $brand->detachMediaTags('logo');
            $brand->attachMedia($request->logo, 'logo');

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'failed to update brand',
            ], 500);

        }

        return response()->json([
            'status' => 'success',
            'message' => 'brand updated succesfully',
        ], 200);
    }

    public function delete($ids)
    {
        $this->authorize('delete_brand');

        $ids = explode(',', $ids);

        try {
            Brand::destroy($ids);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to delete brand',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'brand deleted succesfully',
        ], 204);
    }
}
