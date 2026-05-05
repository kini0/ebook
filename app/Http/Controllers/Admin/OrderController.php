<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderRepositoryInterface $orders,
        protected OrderService $orderService,
    ) {}

    public function index(Request $request)
    {
        return view('admin.orders.index', [
            'orders'  => $this->orders->paginateAdmin($request->only('q', 'status'), 20),
            'filters' => $request->only('q', 'status'),
            'statuses'=> OrderStatus::cases(),
        ]);
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', [
            'order' => $order->load(['items.ebook', 'transactions', 'invoice', 'user', 'downloads']),
        ]);
    }

    public function markPaid(Order $order)
    {
        $this->orderService->markAsPaid($order);
        return back()->with('success', 'Commande marquée comme payée.');
    }

    public function cancel(Order $order)
    {
        $this->orderService->cancel($order);
        return back()->with('success', 'Commande annulée.');
    }
}
