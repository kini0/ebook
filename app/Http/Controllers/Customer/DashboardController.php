<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, OrderRepositoryInterface $orders)
    {
        $user = $request->user();
        return view('customer.dashboard', [
            'recentOrders' => $orders->paginateForUser($user->id, 5),
            'downloadCount'=> $user->downloads()->count(),
            'orderCount'   => $user->orders()->count(),
        ]);
    }
}
