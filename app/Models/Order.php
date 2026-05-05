<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference', 'user_id',
        'billing_email', 'billing_name', 'billing_phone',
        'billing_country', 'billing_city', 'billing_address',
        'status', 'subtotal_cents', 'tax_cents', 'total_cents', 'currency',
        'payment_method', 'placed_at', 'paid_at', 'cancelled_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'status'         => OrderStatus::class,
            'subtotal_cents' => 'integer',
            'tax_cents'      => 'integer',
            'total_cents'    => 'integer',
            'placed_at'      => 'datetime',
            'paid_at'        => 'datetime',
            'cancelled_at'   => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'reference';
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->reference ??= self::generateReference();
            $order->currency  ??= config('payment.currency.code', 'XOF');
        });
    }

    public static function generateReference(): string
    {
        do {
            $ref = 'CMD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('reference', $ref)->exists());
        return $ref;
    }

    /* ---------- Relations ---------- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class);
    }

    /* ---------- Accessors ---------- */

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_cents, 0, ',', ' ') . ' FCFA';
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return number_format($this->subtotal_cents, 0, ',', ' ') . ' FCFA';
    }

    public function isPaid(): bool
    {
        return $this->status === OrderStatus::PAID;
    }

    public function isPending(): bool
    {
        return $this->status === OrderStatus::PENDING;
    }
}
