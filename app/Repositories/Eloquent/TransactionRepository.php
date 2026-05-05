<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function findByProviderReference(string $reference): ?Transaction
    {
        return $this->query()->where('provider_reference', $reference)->first();
    }

    public function paginateAdmin(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->query()
            ->with(['order', 'user'])
            ->when($filters['status'] ?? null, fn ($q, $s) => $q->where('status', $s))
            ->when($filters['gateway'] ?? null, fn ($q, $g) => $q->where('gateway', $g))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }
}
