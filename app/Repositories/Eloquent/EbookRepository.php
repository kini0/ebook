<?php

namespace App\Repositories\Eloquent;

use App\Models\Ebook;
use App\Repositories\Contracts\EbookRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EbookRepository extends BaseRepository implements EbookRepositoryInterface
{
    public function __construct(Ebook $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?Ebook
    {
        return $this->query()->with('category')->where('slug', $slug)->first();
    }

    public function published(): Collection
    {
        return $this->query()->published()->with('category')->get();
    }

    public function featured(int $limit = 6): Collection
    {
        return $this->query()->published()->featured()->with('category')->latest('published_at')->limit($limit)->get();
    }

    public function bestsellers(int $limit = 6): Collection
    {
        return $this->query()->published()->bestsellers()->with('category')->limit($limit)->get();
    }

    public function paginatePublic(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        return $this->query()
            ->published()
            ->with('category')
            ->when($filters['category'] ?? null, fn ($q, $slug) =>
                $q->whereHas('category', fn ($c) => $c->where('slug', $slug))
            )
            ->when($filters['q'] ?? null, fn ($q, $term) => $q->search($term))
            ->when($filters['min'] ?? null, fn ($q, $min) => $q->where('price_cents', '>=', (int) $min))
            ->when($filters['max'] ?? null, fn ($q, $max) => $q->where('price_cents', '<=', (int) $max))
            ->orderBy(
                $filters['sort'] ?? 'published_at',
                $filters['order'] ?? 'desc'
            )
            ->paginate($perPage)
            ->withQueryString();
    }

    public function paginateAdmin(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->query()
            ->with('category')
            ->when($filters['q'] ?? null, fn ($q, $term) => $q->search($term))
            ->when(isset($filters['published']), fn ($q) =>
                $q->where('is_published', (bool) $filters['published'])
            )
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }
}
