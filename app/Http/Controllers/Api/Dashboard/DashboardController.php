<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
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

        $top_selling_products = DB::table('invoices')
            ->leftJoin('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->leftJoin('products', 'invoice_items.product_id', '=', 'products.id')
            ->select([
                'invoice_items.product_id as product_id',
                'products.name as name',
                DB::raw('SUM(invoice_items.product_quantity) as total_quantity_sold'),
            ])
            ->where('invoices.type', 'sale')
            ->groupBy('invoice_items.product_id')
            ->orderByDesc('total_quantity_sold')
            ->limit(3)
            ->get();

        $payment_received_current_week = DB::table('payments')->where('invoice_type','sale')
            ->whereDate('date', '>=', Carbon::now()->startOfWeek())
            ->whereDate('date', '<=', Carbon::now()->endOfWeek())
            ->select([DB::raw('SUM(amount) as amount'), 'date'])
            ->groupBy('date')->orderBy('date')
            ->get();

        $payment_send_current_week = DB::table('payments')->where('invoice_type','purchase')
            ->whereDate('date', '>=', Carbon::now()->startOfWeek())
            ->whereDate('date', '<=', Carbon::now()->endOfWeek())
            ->select([DB::raw('SUM(amount) as amount'), 'date'])
            ->groupBy('date')->orderBy('date')
            ->get();   

        return response()->json([
            'total_sale_amount' => $total_sale_amount,
            'total_sale_due' => $total_sale_due,
            'total_purchase_amount' => $total_purchase_amount,

            'payment_send_current_week' => $payment_send_current_week,
            'payment_received_current_week' => $payment_received_current_week,

            'total_purchase_due' => $total_purchase_due,
            'top_selling_products' => $top_selling_products,
            
            'current_week_sales' => $current_week_sales,
            'current_week_purchases' => $current_week_purchases,
        ]);
    }
}
