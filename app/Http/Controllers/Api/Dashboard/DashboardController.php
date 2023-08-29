<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\ReportService;

class DashboardController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function getDashboardOverview()
    {
        $this->authorize('view_dashboard_overview');

        return response()->json([
            'total_purchase_amount' => $this->reportService->getTotalPurchaseAmount(),
            'total_purchase_due' => $this->reportService->getTotalPurchaseDueAmount(),
            'total_sale_amount' => $this->reportService->getTotalSaleAmount(),
            'total_sale_due' => $this->reportService->getTotalSaleDueAmount(),

            'payment_send_current_week' => $this->reportService->getPaymentsSentCurrentWeek(),
            'payment_received_current_week' => $this->reportService->getPaymentsReceivedCurrentWeek(),

            'top_selling_products' => $this->reportService->getTopSellingProducts(),

            'current_week_sales' => $this->reportService->getCurrentWeekSales(),
            'current_week_purchases' => $this->reportService->getCurrentWeekPurchases(),
        ]);
    }
}
