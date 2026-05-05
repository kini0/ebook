<?php

namespace App\DTOs;

use App\Enums\PaymentStatus;

/**
 * Immutable result of a payment operation.
 *
 * Drivers return this DTO so the calling service is decoupled from any
 * provider-specific response shape.
 */
final class PaymentResult
{
    public function __construct(
        public readonly PaymentStatus $status,
        public readonly ?string $providerReference = null,
        public readonly ?string $redirectUrl = null,
        public readonly ?string $message = null,
        public readonly array $rawResponse = [],
    ) {}

    public static function success(?string $providerRef = null, array $raw = [], ?string $message = null): self
    {
        return new self(PaymentStatus::PAID, $providerRef, null, $message, $raw);
    }

    public static function pending(?string $providerRef = null, array $raw = [], ?string $message = null): self
    {
        return new self(PaymentStatus::PENDING, $providerRef, null, $message, $raw);
    }

    public static function redirect(string $url, ?string $providerRef = null, array $raw = []): self
    {
        return new self(PaymentStatus::PROCESSING, $providerRef, $url, null, $raw);
    }

    public static function failed(?string $message = null, array $raw = []): self
    {
        return new self(PaymentStatus::FAILED, null, null, $message, $raw);
    }

    public function isSuccessful(): bool
    {
        return $this->status === PaymentStatus::PAID;
    }

    public function isPending(): bool
    {
        return in_array($this->status, [PaymentStatus::PENDING, PaymentStatus::PROCESSING], true);
    }

    public function isFailed(): bool
    {
        return $this->status === PaymentStatus::FAILED;
    }

    public function shouldRedirect(): bool
    {
        return $this->redirectUrl !== null;
    }
}
