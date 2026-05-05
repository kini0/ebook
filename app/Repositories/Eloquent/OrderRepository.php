<?php

namespace App\Repositories\Eloquent;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use DateTimeInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function findByReference(string $reference): ?Order
    {
        return $this->query()
            ->with(['items.ebook', 'transactions', 'invoice', 'user'])
            ->where('reference', $reference)
            ->first();
    }

    public function paginateForUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->query()
            ->where('user_id', $userId)
            ->with(['items'])
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function paginateAdmin(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->query()
            ->with(['user', 'items'])
            ->when($filters['status'] ?? null, fn ($q, $s) => $q->where('status', $s))
            ->when($filters['q'] ?? null, function ($q, $term) {
                $like = '%' . $term . '%';
                $q->where(fn ($x) => $x->where('reference', 'like', $like)
                    ->orWhere('billing_email', 'like', $like)
                    ->orWhere('billing_name', 'like', $like));
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function revenueBetween(DateTimeInterface $from, DateTimeInterface $to): int
    {
        return (int) $this->query()
            ->where('status', OrderStatus::PAID->value)
            ->whereBetween('paid_at', [$from, $to])
            ->sum('total_cents');
    }

    public function dailyRevenue(int $days = 30): array
    {
        $rows = $this->query()
            ->selectRaw('DATE(paid_at) as day, SUM(total_cents) as total')
            ->where('status', OrderStatus::PAID->value)
            ->where('paid_at', '>=', now()->subDays($days)->startOfDay())
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $out = [];
        for ($i = $days; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i)->toDateString();
            $out[$day] = (int) ($rows[$day] ?? 0);
        }
        return $out;
    }
}
