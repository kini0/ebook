<?php

namespace App\DTOs;

/**
 * Validated checkout payload, passed from CheckoutController to OrderService.
 */
final class CheckoutData
{
    /**
     * @param array<int, array{ebook_id:int, quantity:int, unit_price_cents:int}> $items
     */
    public function __construct(
        public readonly string $billingName,
        public readonly string $billingEmail,
        public readonly ?string $billingPhone,
        public readonly ?string $billingCountry,
        public readonly ?string $billingCity,
        public readonly ?string $billingAddress,
        public readonly string $paymentMethod,
        public readonly array $paymentPayload,
        public readonly array $items,
    ) {}
}
