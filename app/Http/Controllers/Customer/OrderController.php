<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected OrderRepositoryInterface $orders) {}

    public function index(Request $request)
    {
        return view('customer.orders.index', [
            'orders' => $this->orders->paginateForUser($request->user()->id, 10),
        ]);
    }

    public function show(Order $order, Request $request)
    {
        $this->authorize('view', $order);
        return view('customer.orders.show', [
            'order' => $order->load(['items.ebook', 'transactions', 'invoice']),
        ]);
    }
}
