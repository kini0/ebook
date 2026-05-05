<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference', 'order_id', 'user_id',
        'gateway', 'provider', 'provider_reference',
        'amount_cents', 'currency', 'status',
        'metadata', 'gateway_response', 'failure_reason', 'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_cents'     => 'integer',
            'metadata'         => 'array',
            'gateway_response' => 'array',
            'processed_at'     => 'datetime',
            'status'           => PaymentStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Transaction $tx) {
            $tx->reference ??= 'TX-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(8));
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
