<?php

namespace App\Http\Controllers\Api\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\CreateSaleRequest;
use App\Http\Resources\Invoice\SaleListResource;
use App\Models\Invoice\Invoice;
use App\Services\SaleService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleInvoiceController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function store(CreateSaleRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->saleService->create($request->input());
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
        $this->authorize('view_sale');

        $page = $request->query('page');
        $per_page = $request->query('per_page') && $request->query('per_page') < 100 ? $request->query('per_page') : 10;
        $search = $request->query('search');

        $sales = Invoice::whereType('sale');

        $sales->when($search, function ($query, $search) {
            $query->where('invoice_ref', 'LIKE', '%'.$search.'%');
        });

        $sales = $sales->latest()->paginate($per_page);

        return SaleListResource::collection($sales);
    }
}
