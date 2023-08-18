<?php

namespace App\Http\Controllers\Api\Invoice;

use App\Http\Controllers\Controller;
use App\Services\PurchaseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseInvoiceController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->purchaseService->create($request->input());
            DB::commit();

        } catch (Exception $e) {

            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => $e,
            ], 500);
        }

    }
}
