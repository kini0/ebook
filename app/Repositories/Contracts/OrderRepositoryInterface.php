<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function findByReference(string $reference);

    public function paginateForUser(int $userId, int $perPage = 10): LengthAwarePaginator;

    public function paginateAdmin(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function revenueBetween(\DateTimeInterface $from, \DateTimeInterface $to): int;

    public function dailyRevenue(int $days = 30): array;
}
