<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Ebook;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    public function __construct(protected OrderRepositoryInterface $orders) {}

    /**
     * @return array{revenue_30d:int, revenue_7d:int, revenue_today:int, orders_30d:int, customers:int, ebooks:int}
     */
    public function dashboardSummary(): array
    {
        return Cache::remember('analytics:dashboard', now()->addMinutes(5), function () {
            return [
                'revenue_today' => $this->orders->revenueBetween(now()->startOfDay(), now()->endOfDay()),
                'revenue_7d'    => $this->orders->revenueBetween(now()->subDays(7), now()),
                'revenue_30d'   => $this->orders->revenueBetween(now()->subDays(30), now()),
                'orders_30d'    => Order::where('status', OrderStatus::PAID->value)
                                        ->where('paid_at', '>=', now()->subDays(30))->count(),
                'customers'     => User::where('role', \App\Enums\UserRole::CUSTOMER->value)->count(),
                'ebooks'        => Ebook::published()->count(),
                'pending_tx'    => Transaction::where('status', \App\Enums\PaymentStatus::PENDING->value)->count(),
            ];
        });
    }

    public function revenueChart(int $days = 30): array
    {
        return $this->orders->dailyRevenue($days);
    }

    public function bestsellers(int $limit = 5): \Illuminate\Support\Collection
    {
        return Ebook::query()
            ->orderByDesc('download_count')
            ->limit($limit)
            ->get(['id', 'title', 'author', 'download_count', 'price_cents', 'cover_path']);
    }
}
