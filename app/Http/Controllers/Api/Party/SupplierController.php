<?php

namespace App\Http\Controllers\Api\Party;

use App\Http\Controllers\Controller;
use App\Http\Requests\Party\CreateSupplierRequest;
use App\Http\Requests\Party\UpdateSupplierRequest;
use App\Http\Resources\Party\SupplierDetailsRerource;
use App\Http\Resources\Party\SupplierListCollection;
use App\Models\Party;
use Exception;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_supplier');

        $page = $request->query('page') ?? 1;
        $per_page = $request->query('per_page') && $request->query('per_page') < 100 ? $request->query('per_page') : 10;
        $q_name = $request->query('q_name');
        $q_status = $request->query('q_status');

        $suppliers = Party::query()->where('isSupplier', 1);

        $suppliers->when($q_name, function ($query, $q_name) {
            $query->where('name', 'LIKE', '%'.$q_name.'%');
        })->when($q_status, function ($query, $q_status) {
            $query->where('status', $q_status);
        });

        $suppliers = $suppliers->orderBy('id', 'desc')->paginate($per_page);

        return new SupplierListCollection($suppliers);
    }

    public function search($search)
    {
        $this->authorize('view_customer');
        $supplier = Party::query()->where('isSupplier', 1)->where('status', 'active');

        $supplier->when($search, function ($query, $search) {
            $query->where('name', 'LIKE', '%'.$search.'%');
        });

        $supplier = $supplier->orderBy('id', 'desc')->limit(20)->get();

        return new SupplierListCollection($supplier);
    }

    public function show($supplier_id)
    {
        $this->authorize('view_supplier');

        $supplier = Party::where('id', $supplier_id)->where('isSupplier', 1)->first();

        return new SupplierDetailsRerource($supplier);
    }

    public function store(CreateSupplierRequest $request)
    {
        try {

            $supplier = new Party();

            $supplier->name = $request->validated('name');
            $supplier->phone = $request->validated('phone');
            $supplier->email = $request->validated('email');
            $supplier->tax_number = $request->validated('tax_number');
            $supplier->country = $request->validated('country');
            $supplier->city = $request->validated('city');
            $supplier->postal_code = $request->validated('postal_code');
            $supplier->address = $request->validated('address');
            $supplier->billing_address = $request->validated('billing_address');
            $supplier->shipping_address = $request->validated('shipping_address');
            $supplier->status = $request->validated('status');
            $supplier->isSupplier = 1;
            $request->validated('isCustomer') == 1 ? $supplier->isCustomer = 1 : $supplier->isCustomer = 0;

            $supplier->save();

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to create supplier',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'supplier created succesfully',
        ], 201);
    }

    public function update(UpdateSupplierRequest $request, $supplier_id)
    {
        try {

            $supplier = Party::find($supplier_id);

            $supplier->name = $request->validated('name');
            $supplier->phone = $request->validated('phone');
            $supplier->email = $request->validated('email');
            $supplier->tax_number = $request->validated('tax_number');
            $supplier->country = $request->validated('country');
            $supplier->city = $request->validated('city');
            $supplier->postal_code = $request->validated('postal_code');
            $supplier->address = $request->validated('address');
            $supplier->billing_address = $request->validated('billing_address');
            $supplier->shipping_address = $request->validated('shipping_address');
            $supplier->status = $request->validated('status');
            //$supplier->isCustomer = $request->validated('isCustomer');

            $supplier->save();

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to update supplier',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'supplier updated succesfully',
        ], 200);
    }

    public function delete($ids)
    {
        $this->authorize('delete_supplier');

        $ids = explode(',', $ids);

        try {
            Party::destroy($ids);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to delete supplier',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'supplier deleted succesfully',
        ], 204);
    }
}
