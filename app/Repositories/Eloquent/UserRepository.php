<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->query()->where('email', $email)->first();
    }

    public function paginateAdmin(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->query()
            ->when($filters['q'] ?? null, function ($q, $term) {
                $like = '%' . $term . '%';
                $q->where(fn ($x) => $x->where('first_name', 'like', $like)
                    ->orWhere('last_name', 'like', $like)
                    ->orWhere('email', 'like', $like));
            })
            ->when($filters['role'] ?? null, fn ($q, $r) => $q->where('role', $r))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }
}
