<?php

namespace App\Payments\Drivers;

use App\DTOs\PaymentResult;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * Card driver — abstracts the card processor (Stripe, CinetPay, etc.).
 *
 * The "provider" config value selects the underlying processor. This class
 * exposes a simple, stable surface to the rest of the application.
 *
 * In production, replace the stub bodies with real SDK calls. The
 * scaffolding is webhook-ready (handleWebhook + verify) and idempotent.
 */
class CardDriver extends AbstractPaymentDriver
{
    public function key(): string
    {
        return 'card';
    }

    public function initiate(Order $order, array $payload = []): PaymentResult
    {
        $provider = (string) ($this->config['provider'] ?? 'stripe');

        // Stub: in production, call Stripe\PaymentIntent::create or CinetPay payment endpoint.
        // We return a redirect to a hosted checkout page (most secure flow).
        $providerRef = $this->buildProviderReference($order->reference);

        // Simulate hosted-page URL. Replace with real SDK call.
        $hostedUrl = url(sprintf('/payment/%s/process?ref=%s', $order->reference, $providerRef));

        return PaymentResult::redirect(
            url: $hostedUrl,
            providerRef: $providerRef,
            raw: ['provider' => $provider, 'mode' => 'hosted'],
        );
    }

    public function verify(string $providerReference): PaymentResult
    {
        // Stub: call Stripe\PaymentIntent::retrieve or CinetPay /payment/check
        return PaymentResult::pending($providerReference, ['stub' => true]);
    }

    public function handleWebhook(Request $request): PaymentResult
    {
        // Stub: verify signature, parse event type, return mapped PaymentResult.
        $event = $request->input('event', $request->input('type'));
        $ref   = $request->input('reference', $request->input('data.id'));

        return match ($event) {
            'payment.succeeded', 'payment_intent.succeeded' => PaymentResult::success($ref, $request->all()),
            'payment.failed', 'payment_intent.payment_failed' => PaymentResult::failed('Paiement refusé', $request->all()),
            default => PaymentResult::pending($ref, $request->all()),
        };
    }
}
