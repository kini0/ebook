<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'number', 'order_id', 'user_id',
        'subtotal_cents', 'tax_cents', 'total_cents', 'currency',
        'issue_date', 'due_date', 'pdf_path', 'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal_cents' => 'integer',
            'tax_cents'      => 'integer',
            'total_cents'    => 'integer',
            'issue_date'     => 'date',
            'due_date'       => 'date',
            'sent_at'        => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'number';
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_cents, 0, ',', ' ') . ' FCFA';
    }
}
