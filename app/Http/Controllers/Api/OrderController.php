<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected OrderRepositoryInterface $orders) {}

    public function index(Request $request)
    {
        return OrderResource::collection(
            $this->orders->paginateForUser($request->user()->id, 15)
        );
    }

    public function show(Order $order): OrderResource
    {
        $this->authorize('view', $order);
        return new OrderResource($order->load(['items.ebook', 'transactions', 'invoice']));
    }
}
