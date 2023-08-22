<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getDashboardOverview()
    {
        $this->authorize('view_dashboard_overview');
        
        $total_purchase_amount = DB::table('invoices')->where('type', 'purchase')->sum('total_amount');
        $total_purchase_due = DB::table('invoices')->where('type', 'purchase')->sum('due_amount');
        $total_sale_amount = DB::table('invoices')->where('type', 'sale')->sum('total_amount');
        $total_sale_due = DB::table('invoices')->where('type', 'sale')->sum('due_amount');

        return response()->json([
            'total_sale_amount' => $total_sale_amount,
            'total_sale_due' => $total_sale_due,
            'total_purchase_amount' => $total_purchase_amount,
            'total_purchase_due' => $total_purchase_due,
        ]);
    }
}
