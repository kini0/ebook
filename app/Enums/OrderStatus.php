<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING   = 'pending';
    case PAID      = 'paid';
    case CANCELLED = 'cancelled';
    case REFUNDED  = 'refunded';
    case FAILED    = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING   => 'En attente',
            self::PAID      => 'Payée',
            self::CANCELLED => 'Annulée',
            self::REFUNDED  => 'Remboursée',
            self::FAILED    => 'Échouée',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PAID      => 'badge-success',
            self::PENDING   => 'badge-warning',
            self::FAILED, self::CANCELLED => 'badge-danger',
            self::REFUNDED  => 'badge-gold',
        };
    }
}
