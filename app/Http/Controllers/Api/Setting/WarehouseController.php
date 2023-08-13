<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouse\CreateWarehouseRequest;
use App\Http\Requests\Warehouse\UpdateWarehouseRequest;
use App\Http\Resources\Warehouse\WarehouseCollection;
use App\Http\Resources\Warehouse\WarehouseResource;
use App\Models\Setting\Warehouse;
use Exception;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_warehouse');

        $page = $request->query('page');
        $per_page = $request->query('per_page', 10);
        $q_name = $request->query('q_name');

        $warehouses = Warehouse::query();

        $warehouses->when($q_name, function ($query, $q_name) {
            $query->where('name', 'LIKE', '%'.$q_name.'%');
        });

        $warehouses = $page ? $warehouses->orderBy('id', 'desc')->paginate($per_page)
            : $warehouses->orderBy('id', 'desc')->get();

        return new WarehouseCollection($warehouses);
    }

    public function show($warehouse_id)
    {
        $this->authorize('view_warehouse');

        $warehouse = Warehouse::where('id', $warehouse_id)->first();

        return new WarehouseResource($warehouse);
    }

    public function store(CreateWarehouseRequest $request)
    {
        try {

            Warehouse::create($request->validated());

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'failed to create warehouse',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'warehouse created succesfully',
        ], 201);
    }

    public function update(UpdateWarehouseRequest $request, $warehouse_id)
    {
        try {
            Warehouse::where('id', $warehouse_id)->update($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to update warehouse',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'warehouse updated succesfully',
        ], 200);
    }

    public function delete($ids)
    {
        $this->authorize('delete_warehouse');

        $ids = explode(',', $ids);

        try {

            Warehouse::whereIn('id', $ids)->delete();

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'failed to delete warehouse',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'warehouse deleted succesfully',
        ], 204);

    }
}
