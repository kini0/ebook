<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TransactionRepositoryInterface extends BaseRepositoryInterface
{
    public function findByProviderReference(string $reference);

    public function paginateAdmin(array $filters = [], int $perPage = 20): LengthAwarePaginator;
}
