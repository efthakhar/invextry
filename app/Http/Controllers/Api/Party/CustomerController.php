<?php

namespace App\Http\Controllers\Api\Party;

use App\Http\Controllers\Controller;
use App\Http\Requests\Party\CreateCustomerRequest;
use App\Http\Requests\Party\UpdateCustomerRequest;
use App\Http\Resources\Party\CustomerDetailsRerource;
use App\Http\Resources\Party\CustomerListCollection;
use App\Models\Party;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_customer');

        $page = $request->query('page') ?? 1;
        $per_page = $request->query('per_page') && $request->query('per_page') < 100 ? $request->query('per_page') : 10;
        $q_name = $request->query('q_name');
        $q_status = $request->query('q_status');

        $customers = Party::query()->where('isCustomer', 1);

        $customers->when($q_name, function ($query, $q_name) {
            $query->where('name', 'LIKE', '%'.$q_name.'%');
        })->when($q_status, function ($query, $q_status) {
            $query->where('status', $q_status);
        });

        $customers = $customers->orderBy('id', 'desc')->paginate($per_page);

        return new CustomerListCollection($customers);
    }

    public function search($search)
    {
        $this->authorize('view_customer');
        $customers = Party::query()->where('isCustomer', 1)->where('status', 'active');

        $customers->when($search, function ($query, $search) {
            $query->where('name', 'LIKE', '%'.$search.'%');
        });

        $customers = $customers->orderBy('id', 'desc')->limit(20)->get();

        return new CustomerListCollection($customers);
    }

    public function show($customer_id)
    {
        $this->authorize('view_customer');

        $customer = Party::where('id', $customer_id)->where('isCustomer', 1)->first();

        return new CustomerDetailsRerource($customer);
    }

    public function store(CreateCustomerRequest $request)
    {
        try {

            $customer = new Party();

            $customer->name = $request->validated('name');
            $customer->phone = $request->validated('phone');
            $customer->email = $request->validated('email');
            $customer->tax_number = $request->validated('tax_number');
            $customer->country = $request->validated('country');
            $customer->city = $request->validated('city');
            $customer->postal_code = $request->validated('postal_code');
            $customer->address = $request->validated('address');
            $customer->billing_address = $request->validated('billing_address');
            $customer->shipping_address = $request->validated('shipping_address');
            $customer->status = $request->validated('status');
            $customer->isCustomer = 1;
            $request->validated('isSupplier') == 1 ? $customer->isSupplier = 1 : $customer->isSupplier = 0;

            $customer->save();

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to create customer',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'customer created succesfully',
        ], 201);
    }

    public function update(UpdateCustomerRequest $request, $customer_id)
    {
        try {

            $customer = Party::find($customer_id);

            $customer->name = $request->validated('name');
            $customer->phone = $request->validated('phone');
            $customer->email = $request->validated('email');
            $customer->tax_number = $request->validated('tax_number');
            $customer->country = $request->validated('country');
            $customer->city = $request->validated('city');
            $customer->postal_code = $request->validated('postal_code');
            $customer->address = $request->validated('address');
            $customer->billing_address = $request->validated('billing_address');
            $customer->shipping_address = $request->validated('shipping_address');
            $customer->status = $request->validated('status');
            //$customer->isSupplier = $request->validated('isSupplier');

            $customer->save();

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to update customer',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'customer updated succesfully',
        ], 200);
    }

    public function delete($ids)
    {
        $this->authorize('delete_customer');

        $ids = explode(',', $ids);

        try {
            Party::destroy($ids);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to delete customer',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'customer deleted succesfully',
        ], 204);
    }
}
