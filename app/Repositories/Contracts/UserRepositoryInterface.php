<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail(string $email);

    public function paginateAdmin(array $filters = [], int $perPage = 20): LengthAwarePaginator;
}
