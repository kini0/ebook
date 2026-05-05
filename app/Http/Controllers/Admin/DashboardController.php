<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\AnalyticsService;

class DashboardController extends Controller
{
    public function __invoke(AnalyticsService $analytics)
    {
        return view('admin.dashboard', [
            'summary'      => $analytics->dashboardSummary(),
            'revenueChart' => $analytics->revenueChart(30),
            'bestsellers'  => $analytics->bestsellers(5),
            'recentOrders' => Order::with('user')->latest()->limit(8)->get(),
        ]);
    }
}
