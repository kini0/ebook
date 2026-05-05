<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Ebook extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'title', 'slug', 'subtitle', 'author',
        'short_description', 'description', 'isbn', 'language', 'pages',
        'price_cents', 'compare_at_cents',
        'cover_path', 'file_path', 'file_format', 'file_size_bytes',
        'download_count', 'view_count',
        'is_featured', 'is_published', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'price_cents'      => 'integer',
            'compare_at_cents' => 'integer',
            'pages'            => 'integer',
            'file_size_bytes'  => 'integer',
            'download_count'   => 'integer',
            'view_count'       => 'integer',
            'is_featured'      => 'boolean',
            'is_published'     => 'boolean',
            'published_at'     => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /* ---------- Relations ---------- */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class);
    }

    /* ---------- Accessors ---------- */

    public function getPriceAttribute(): float
    {
        return $this->price_cents; // FCFA stored as integer (no fractional unit)
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price_cents, 0, ',', ' ') . ' FCFA';
    }

    public function getFormattedCompareAtAttribute(): ?string
    {
        return $this->compare_at_cents
            ? number_format($this->compare_at_cents, 0, ',', ' ') . ' FCFA'
            : null;
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if (! $this->compare_at_cents || $this->compare_at_cents <= $this->price_cents) {
            return null;
        }
        return (int) round(($this->compare_at_cents - $this->price_cents) / $this->compare_at_cents * 100);
    }

    public function getCoverUrlAttribute(): string
    {
        return $this->cover_path
            ? Storage::disk('public')->url($this->cover_path)
            : asset('images/cover-placeholder.png');
    }

    /* ---------- Scopes ---------- */

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true);
    }

    public function scopeBestsellers(Builder $q): Builder
    {
        return $q->orderByDesc('download_count');
    }

    public function scopeSearch(Builder $q, ?string $term): Builder
    {
        if (! $term) {
            return $q;
        }
        $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';
        return $q->where(function ($s) use ($like) {
            $s->where('title', 'like', $like)
              ->orWhere('author', 'like', $like)
              ->orWhere('short_description', 'like', $like);
        });
    }
}
