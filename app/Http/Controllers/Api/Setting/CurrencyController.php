<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Currency\CreateCurrencyRequest;
use App\Http\Requests\Currency\UpdateCurrencyRequest;
use App\Http\Resources\Currency\CurrencyCollection;
use App\Http\Resources\Currency\CurrencyResource;
use App\Models\Setting\Currency;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_currency');

        $page = $request->query('page');
        $per_page = $request->query('per_page', 10);
        $q_name = $request->query('q_name');

        $currencies = Currency::query();

        $currencies->when($q_name, function ($query, $q_name) {
            $query->where('name', 'LIKE', '%'.$q_name.'%');
        });

        $currencies = $page ? $currencies->orderBy('id', 'desc')->paginate($per_page)
            : $currencies->orderBy('id', 'desc')->get();

        return new CurrencyCollection($currencies);
    }

    public function show($currency_id)
    {
        $this->authorize('view_currency');

        $currency = Currency::where('id', $currency_id)->first();

        return new CurrencyResource($currency);
    }

    public function store(CreateCurrencyRequest $request)
    {
        try {

            Currency::create($request->validated());

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'failed to create currency',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'currency created succesfully',
        ], 201);
    }

    public function update(UpdateCurrencyRequest $request, $currency_id)
    {
        try {
            Currency::where('id', $currency_id)->update($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to update currency',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'currency updated succesfully',
        ], 200);
    }

    public function delete($ids)
    {
        $this->authorize('delete_currency');

        $ids = explode(',', $ids);

        try {

            Currency::whereIn('id', $ids)->delete();

        } catch (QueryException $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'currency deleted failed',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'currency deleted succesfully',
        ], 204);

    }
}
