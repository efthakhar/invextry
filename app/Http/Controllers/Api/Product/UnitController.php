<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\CreateUnitRequest;
use App\Http\Requests\Unit\UpdateUnitRequest;
use App\Http\Resources\Unit\UnitCollection;
use App\Http\Resources\Unit\UnitResource;
use App\Models\Product\Unit;
use Exception;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_unit');

        $page = $request->query('page');
        $per_page = $request->query('per_page', 10);
        $q_name = $request->query('q_name');

        $units = Unit::query();

        $units->when($q_name, function ($query, $q_name) {
            $query->where('name', 'LIKE', '%'.$q_name.'%');
        });

        $units = $page ? $units->orderBy('id', 'desc')->paginate($per_page)
            : $units->orderBy('id', 'desc')->get();

        return new UnitCollection($units);
    }

    public function show($unit_id)
    {
        $this->authorize('view_unit');

        $unit = Unit::where('id', $unit_id)->first();

        return new UnitResource($unit);
    }

    public function store(CreateUnitRequest $request)
    {
        try {

            Unit::create($request->validated());

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'failed to create unit',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'unit created succesfully',
        ], 201);
    }

    public function update(UpdateUnitRequest $request, $unit_id)
    {
        try {
            Unit::where('id', $unit_id)->update($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to update unit',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'unit updated succesfully',
        ], 200);
    }

    public function delete($ids)
    {
        $this->authorize('delete_unit');

        $ids = explode(',', $ids);

        try {

            Unit::whereIn('id', $ids)->delete();

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'failed to delete unit',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'unit deleted succesfully',
        ], 204);

    }

    public function getBaseUnits()
    {
        $units = Unit::where('base_unit_id', null)->get();

        return new UnitCollection($units);
    }

    public function getHomogeneousUnits($id)
    {
        $units = Unit::where('base_unit_id', $id)->orWhere('id', $id)->get();

        return new UnitCollection($units);
    }
}
