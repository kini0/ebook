<?php

namespace App\Payments\Drivers;

use App\DTOs\PaymentResult;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * Bank transfer driver — manual reconciliation.
 *
 * On initiate, returns PENDING with the bank details to display to the user.
 * Admins later mark the transaction as paid in the dashboard once they
 * have reconciled the inbound transfer.
 */
class BankTransferDriver extends AbstractPaymentDriver
{
    public function key(): string
    {
        return 'bank_transfer';
    }

    public function initiate(Order $order, array $payload = []): PaymentResult
    {
        $providerRef = $this->buildProviderReference($order->reference);

        return PaymentResult::pending(
            providerRef: $providerRef,
            raw: [
                'bank_name'   => $this->config['bank_name'] ?? null,
                'iban'        => $this->config['iban'] ?? null,
                'rib'         => $this->config['rib'] ?? null,
                'beneficiary' => $this->config['beneficiary'] ?? null,
                'reference'   => $order->reference,
            ],
            message: "Effectuez votre virement avec la référence {$order->reference}. Le paiement sera validé manuellement."
        );
    }

    public function verify(string $providerReference): PaymentResult
    {
        return PaymentResult::pending($providerReference, ['note' => 'Manual reconciliation']);
    }

    public function handleWebhook(Request $request): PaymentResult
    {
        // Bank transfers are reconciled manually by an admin.
        return PaymentResult::pending(null, $request->all());
    }
}
