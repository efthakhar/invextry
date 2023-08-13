<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tax\CreateTaxRequest;
use App\Http\Requests\Tax\UpdateTaxRequest;
use App\Http\Resources\Tax\TaxCollection;
use App\Http\Resources\Tax\TaxResource;
use App\Models\Setting\Tax;
use Exception;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_tax');

        $page = $request->query('page');
        $per_page = $request->query('per_page', 10);
        $q_name = $request->query('q_name');

        $taxes = Tax::query();

        $taxes->when($q_name, function ($query, $q_name) {
            $query->where('name', 'LIKE', '%'.$q_name.'%');
        });

        $taxes = $page ? $taxes->orderBy('id', 'desc')->paginate($per_page)
            : $taxes->orderBy('id', 'desc')->get();

        return new TaxCollection($taxes);
    }

    public function show($tax_id)
    {
        $this->authorize('view_tax');

        $tax = Tax::where('id', $tax_id)->first();

        return new TaxResource($tax);
    }

    public function store(CreateTaxRequest $request)
    {
        try {

            Tax::create($request->validated());

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'failed to create tax',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'tax created succesfully',
        ], 201);
    }

    public function update(UpdateTaxRequest $request, $tax_id)
    {
        try {
            Tax::where('id', $tax_id)->update($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to update tax',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'tax updated succesfully',
        ], 200);
    }

    public function delete($ids)
    {
        $this->authorize('delete_tax');

        $ids = explode(',', $ids);

        try {

            Tax::whereIn('id', $ids)->delete();

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'tax deleted failed',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'tax deleted succesfully',
        ], 204);

    }
}
