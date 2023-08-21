<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\CreateAccountRequest;
use App\Http\Requests\Accounting\UpdateAccountRequest;
use App\Http\Resources\Accounting\AccountResource;
use App\Models\Accounting\Account;
use Exception;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_account');

        $page = $request->query('page');
        $per_page = $request->query('per_page', 10);
        $q_name = $request->query('q_name');

        $accounts = Account::query();

        $accounts->when($q_name, function ($query, $q_name) {
            $query->where('name', 'LIKE', '%'.$q_name.'%');
        });

        $accounts = $page ? $accounts->orderBy('id', 'desc')->paginate($per_page)
            : $accounts->orderBy('id', 'desc')->get();

        return AccountResource::collection($accounts);
    }

    public function list(Request $request)
    {
        $this->authorize('view_account');

        $q_name = $request->query('q_name');

        $accounts = Account::query();

        $accounts->when($q_name, function ($query, $q_name) {
            $query->where('name', 'LIKE', '%'.$q_name.'%');
        });

        $accounts = $accounts->where('status', 'active')->orderBy('id', 'desc')->get();

        return AccountResource::collection($accounts);
    }

    public function show($account_id)
    {
        $this->authorize('view_account');

        $account = Account::where('id', $account_id)->first();

        return new AccountResource($account);
    }

    public function store(CreateAccountRequest $request)
    {
        try {

            Account::create($request->validated());

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'failed to create account',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'account created succesfully',
        ], 201);
    }

    public function update(UpdateAccountRequest $request, $account_id)
    {
        try {
            Account::where('id', $account_id)->update($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'failed to update account',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'account updated succesfully',
        ], 200);
    }

    public function delete($ids)
    {
        $this->authorize('delete_account');

        $ids = explode(',', $ids);

        try {

            Account::whereIn('id', $ids)->delete();

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'failed to delete account',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'account deleted succesfully',
        ], 204);

    }
}
