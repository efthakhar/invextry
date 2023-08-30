<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getTotalPurchaseAmount()
    {
        return DB::table('invoices')->where('type', 'purchase')->sum('total_amount');
    }

    public function getTotalPurchaseDueAmount()
    {
        return DB::table('invoices')->where('type', 'purchase')->sum('due_amount');
    }

    public function getTotalSaleAmount()
    {
        return DB::table('invoices')->where('type', 'sale')->sum('total_amount');
    }

    public function getTotalSaleDueAmount()
    {
        return DB::table('invoices')->where('type', 'sale')->sum('due_amount');
    }

    public function getCurrentWeekSales()
    {
        return DB::table('invoices')->where('type', 'sale')
            ->whereDate('invoice_date', '>=', Carbon::now()->startOfWeek())
            ->whereDate('invoice_date', '<=', Carbon::now()->endOfWeek())
            ->select([DB::raw('SUM(total_amount) as amount'), 'invoice_date as date'])
            ->groupBy('date')->orderBy('date')
            ->get();
    }

    public function getCurrentWeekPurchases()
    {
        return DB::table('invoices')->where('type', 'purchase')
            ->whereDate('invoice_date', '>=', Carbon::now()->startOfWeek())
            ->whereDate('invoice_date', '<=', Carbon::now()->endOfWeek())
            ->select([DB::raw('SUM(total_amount) as amount'), 'invoice_date as date'])
            ->groupBy('date')->orderBy('date')
            ->get();
    }

    public function getTopSellingProducts()
    {
        // return DB::table('invoices')
        //     ->leftJoin('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
        //     ->leftJoin('products', 'invoice_items.product_id', '=', 'products.id')
        //     ->select([
        //         'invoice_items.product_id as product_id',
        //         'products.name as name',
        //         DB::raw('SUM(invoice_items.product_quantity) as total_quantity_sold'),
        //     ])
        //     ->where('invoices.type', 'sale')
        //     ->groupBy('invoice_items.product_id')
        //     ->orderByDesc('total_quantity_sold')
        //     ->limit(3)
        //     ->get();
        return DB::table('invoices')
        ->leftJoin('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
        ->leftJoin('products', 'invoice_items.product_id', '=', 'products.id')
        ->select([
            'invoice_items.product_id as product_id',
            'products.name as name',
            DB::raw('SUM(invoice_items.product_quantity) as total_quantity_sold'),
        ])
        ->where('invoices.type', 'sale')
        ->groupBy('invoice_items.product_id', 'products.name') // Include products.name in the GROUP BY clause
        ->orderByDesc('total_quantity_sold')
        ->limit(3)
        ->get();
    }

    public function getPaymentsReceivedCurrentWeek()
    {
        return DB::table('payments')->where('invoice_type', 'sale')
            ->whereDate('date', '>=', Carbon::now()->startOfWeek())
            ->whereDate('date', '<=', Carbon::now()->endOfWeek())
            ->select([DB::raw('SUM(amount) as amount'), 'date'])
            ->groupBy('date')->orderBy('date')
            ->get();
    }

    public function getPaymentsSentCurrentWeek()
    {
        return DB::table('payments')->where('invoice_type', 'purchase')
            ->whereDate('date', '>=', Carbon::now()->startOfWeek())
            ->whereDate('date', '<=', Carbon::now()->endOfWeek())
            ->select([DB::raw('SUM(amount) as amount'), 'date'])
            ->groupBy('date')->orderBy('date')
            ->get();
    }
}
