<?php

namespace App\Payments\Drivers;

use App\DTOs\PaymentResult;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * Mobile Money driver — covers Orange Money, MTN MoMo, Wave.
 *
 * The selected operator is supplied at runtime in the payload:
 *     ['operator' => 'orange', 'phone' => '+225...']
 *
 * Most West/Central African mobile money APIs follow the same flow:
 *     1. Push payment request to user's phone (USSD).
 *     2. Return PENDING status with provider reference.
 *     3. Provider POSTs to webhook when user confirms (or times out).
 */
class MobileMoneyDriver extends AbstractPaymentDriver
{
    public function key(): string
    {
        return 'mobile_money';
    }

    public function initiate(Order $order, array $payload = []): PaymentResult
    {
        $operator = (string) ($payload['operator'] ?? 'orange');
        $phone    = (string) ($payload['phone'] ?? $order->billing_phone ?? '');

        if (! preg_match('/^\+?\d{8,15}$/', preg_replace('/\s+/', '', $phone))) {
            return PaymentResult::failed('Numéro Mobile Money invalide.');
        }

        if (! isset($this->config['operators'][$operator])) {
            return PaymentResult::failed("Opérateur Mobile Money non pris en charge.");
        }

        $providerRef = $this->buildProviderReference($order->reference);

        // Stub: in production, call Orange Money / MTN MoMo / Wave SDK or REST endpoint.
        // Return PENDING — the customer must approve on their phone via USSD.
        return PaymentResult::pending(
            providerRef: $providerRef,
            raw: [
                'operator' => $operator,
                'phone'    => $phone,
                'message'  => 'Paiement initié. Validez sur votre téléphone via le code USSD.',
            ],
            message: 'Paiement initié. Validez sur votre téléphone.'
        );
    }

    public function verify(string $providerReference): PaymentResult
    {
        // Stub: call provider /transactions/{ref}/status
        return PaymentResult::pending($providerReference, ['stub' => true]);
    }

    public function handleWebhook(Request $request): PaymentResult
    {
        $status = strtolower((string) $request->input('status', ''));
        $ref    = (string) $request->input('reference', $request->input('transaction_id', ''));

        return match (true) {
            in_array($status, ['success', 'completed', 'paid', 'accepted']) =>
                PaymentResult::success($ref, $request->all()),
            in_array($status, ['failed', 'rejected', 'expired', 'cancelled']) =>
                PaymentResult::failed($request->input('reason', 'Paiement refusé'), $request->all()),
            default => PaymentResult::pending($ref, $request->all()),
        };
    }
}
