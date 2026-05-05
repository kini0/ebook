<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface EbookRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug(string $slug);

    public function published(): Collection;

    public function featured(int $limit = 6): Collection;

    public function bestsellers(int $limit = 6): Collection;

    public function paginatePublic(array $filters = [], int $perPage = 12): LengthAwarePaginator;

    public function paginateAdmin(array $filters = [], int $perPage = 20): LengthAwarePaginator;
}
