<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\CreateAdjustmentRequest;
use App\Http\Requests\Accounting\UpdateAdjustmentRequest;
use App\Http\Resources\Accounting\AdjustmentResource;
use App\Models\Accounting\AccountAdjustment;
use App\Services\AccountAdjustmentService;
use Exception;
use Illuminate\Http\Request;

class AccountAdjustmentController extends Controller
{
    protected $adjustmentService;

    function __construct(AccountAdjustmentService $adjustmentService)
    {
        $this->adjustmentService = $adjustmentService;
    }

    public function index(Request $request)
    {
        $this->authorize('view_account_adjustment');

        $page = $request->query('page');
        $per_page = $request->query('per_page') && $request->query('per_page') < 100 ? $request->query('per_page') : 10;
        $serach = $request->query('serach');

        $adjustments = AccountAdjustment::with('account');

        $adjustments->when($serach, function ($query, $serach) {
            $query->where('account.name', 'LIKE', '%'.$serach.'%')
                ->orWhere('account.bank_name', 'LIKE', '%'.$serach.'%')
                ->orWhere('account.branch_name', 'LIKE', '%'.$serach.'%');
        });

        $adjustments = $adjustments->orderBy('id', 'desc')->paginate($per_page);

        return AdjustmentResource::collection($adjustments);
    }

    public function show($id)
    {
        $this->authorize('view_account_adjustment');

        $account = AccountAdjustment::where('id', $id)->first();

        return new AdjustmentResource($account);
    }

    public function store(CreateAdjustmentRequest $request)
    {
        try {
            
            $this->adjustmentService->createAdjustment($request->validated());

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

    // public function update(UpdateAdjustmentRequest $request, $account_id)
    // {

    // }

    // public function delete($ids)
    // {

    // }
}
