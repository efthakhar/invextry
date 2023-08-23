<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
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

        $current_week_sales = DB::table('invoices')->where('type', 'sale')
        ->whereDate('invoice_date', '>=', Carbon::now()->startOfWeek())
        ->whereDate('invoice_date', '<=', Carbon::now()->endOfWeek())
        ->select([DB::raw('SUM(total_amount) as amount'), 'invoice_date as date'])
        ->groupBy('date')->orderBy('date')
        ->get();

        $current_week_purchases = DB::table('invoices')->where('type', 'purchase')
        ->whereDate('invoice_date', '>=', Carbon::now()->startOfWeek())
        ->whereDate('invoice_date', '<=', Carbon::now()->endOfWeek())
        ->select([DB::raw('SUM(total_amount) as amount'), 'invoice_date as date'])
        ->groupBy('date')->orderBy('date')
        ->get();

        return response()->json([
            'total_sale_amount' => $total_sale_amount,
            'total_sale_due' => $total_sale_due,
            'total_purchase_amount' => $total_purchase_amount,
            'total_purchase_due' => $total_purchase_due,
            'current_week_sales' => $current_week_sales,
            'current_week_purchases' => $current_week_purchases,
        ]);
    }
}
