<?php

namespace App\Http\Controllers\Api\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\CreatePurchaseRequest;
use App\Http\Resources\Invoice\PurchaseListResource;
use App\Models\Invoice\Invoice;
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

    public function store(CreatePurchaseRequest $request)
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

    public function index(Request $request)
    {
        $this->authorize('view_purchase');

        $page = $request->query('page');
        $per_page = $request->query('per_page') && $request->query('per_page') < 100 ? $request->query('per_page') : 10;
        $search = $request->query('search');

        $purchases = Invoice::where('type', 'purchase');

        $purchases->when($search, function ($query, $search) {
            $query->where('invoice_ref', 'LIKE', '%'.$search.'%');
        });

        $purchases = $page ? $purchases->orderBy('id', 'desc')->paginate($per_page)
            : $purchases->orderBy('id', 'desc')->get();

        return PurchaseListResource::collection($purchases);
    }
}
