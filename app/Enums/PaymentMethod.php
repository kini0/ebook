<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CARD          = 'card';
    case MOBILE_MONEY  = 'mobile_money';
    case BANK_TRANSFER = 'bank_transfer';

    public function label(): string
    {
        return match ($this) {
            self::CARD          => 'Carte bancaire',
            self::MOBILE_MONEY  => 'Mobile Money',
            self::BANK_TRANSFER => 'Virement bancaire',
        };
    }
}
