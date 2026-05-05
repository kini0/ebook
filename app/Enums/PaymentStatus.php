<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING    = 'pending';
    case PROCESSING = 'processing';
    case PAID       = 'paid';
    case FAILED     = 'failed';
    case REFUNDED   = 'refunded';
    case CANCELLED  = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING    => 'En attente',
            self::PROCESSING => 'En cours',
            self::PAID       => 'Payée',
            self::FAILED     => 'Échouée',
            self::REFUNDED   => 'Remboursée',
            self::CANCELLED  => 'Annulée',
        };
    }
}
