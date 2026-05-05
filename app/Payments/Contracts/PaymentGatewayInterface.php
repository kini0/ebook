<?php

namespace App\Payments\Contracts;

use App\DTOs\PaymentResult;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * Contract every payment gateway driver must satisfy.
 *
 * The PaymentManager resolves a driver by key (e.g. "card", "mobile_money")
 * and the calling service (PaymentService) consumes only this interface,
 * never a concrete driver. This is the Strategy pattern in practice.
 */
interface PaymentGatewayInterface
{
    /**
     * Unique identifier for the gateway (matches config/payment.php key).
     */
    public function key(): string;

    /**
     * Human-readable label (FR) shown on the checkout UI.
     */
    public function label(): string;

    /**
     * Initiate a payment for the given Order.
     *
     * Returns a PaymentResult that may be:
     *   - completed (PaymentStatus::PAID)        e.g. instant capture
     *   - pending   (PaymentStatus::PENDING)     e.g. awaiting USSD confirmation
     *   - redirect  (PaymentResult::redirect())  e.g. hosted payment page
     *
     * @param array<string, mixed> $payload Method-specific data (phone, token, etc.)
     */
    public function initiate(Order $order, array $payload = []): PaymentResult;

    /**
     * Verify the status of a previously-initiated payment, given its provider reference.
     */
    public function verify(string $providerReference): PaymentResult;

    /**
     * Process an inbound webhook from the provider.
     */
    public function handleWebhook(Request $request): PaymentResult;
}
