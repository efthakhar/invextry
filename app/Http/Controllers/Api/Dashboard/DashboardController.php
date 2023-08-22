<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboardOverview()
    {
        $this->authorize('view_dashboard_overview');
    }
}
