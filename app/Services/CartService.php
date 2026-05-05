<?php

namespace App\Services;

use App\Models\Ebook;
use Illuminate\Support\Facades\Session;

/**
 * Session-based cart. Single quantity per ebook (digital goods).
 */
class CartService
{
    private const KEY = 'cart';

    /**
     * @return array<int, array{ebook_id:int, title:string, author:string, unit_price_cents:int, quantity:int, total_cents:int, cover_url:string, slug:string}>
     */
    public function items(): array
    {
        return Session::get(self::KEY, []);
    }

    public function add(Ebook $ebook): void
    {
        $items = $this->items();
        $items[$ebook->id] = [
            'ebook_id'         => $ebook->id,
            'title'            => $ebook->title,
            'author'           => $ebook->author,
            'unit_price_cents' => (int) $ebook->price_cents,
            'quantity'         => 1, // digital goods: always 1
            'total_cents'      => (int) $ebook->price_cents,
            'cover_url'        => $ebook->cover_url,
            'slug'             => $ebook->slug,
        ];
        Session::put(self::KEY, $items);
    }

    public function remove(int $ebookId): void
    {
        $items = $this->items();
        unset($items[$ebookId]);
        Session::put(self::KEY, $items);
    }

    public function clear(): void
    {
        Session::forget(self::KEY);
    }

    public function count(): int
    {
        return count($this->items());
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function has(int $ebookId): bool
    {
        return array_key_exists($ebookId, $this->items());
    }

    public function subtotalCents(): int
    {
        return array_sum(array_column($this->items(), 'total_cents'));
    }

    public function formattedSubtotal(): string
    {
        return number_format($this->subtotalCents(), 0, ',', ' ') . ' FCFA';
    }
}
