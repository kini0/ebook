<?php

namespace App\Services;

use App\DTOs\PaymentResult;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Transaction;
use App\Payments\PaymentManager;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        protected PaymentManager $paymentManager,
        protected TransactionRepositoryInterface $transactions,
        protected OrderService $orderService,
    ) {}

    /**
     * Initiate a payment for an order using the requested gateway.
     *
     * Always creates a Transaction row (auditability), then defers the
     * provider call to the driver. The Transaction is updated with the
     * driver's PaymentResult.
     */
    public function initiate(Order $order, string $gatewayKey, array $payload = []): array
    {
        $gateway = $this->paymentManager->gateway($gatewayKey);

        $transaction = Transaction::create([
            'order_id'     => $order->id,
            'user_id'      => $order->user_id,
            'gateway'      => $gatewayKey,
            'provider'     => $payload['operator'] ?? config("payment.drivers.{$gatewayKey}.provider"),
            'amount_cents' => $order->total_cents,
            'currency'     => $order->currency,
            'status'       => PaymentStatus::PENDING->value,
            'metadata'     => $payload,
        ]);

        try {
            $result = $gateway->initiate($order, $payload);
        } catch (\Throwable $e) {
            Log::error('Payment initiate failed', ['order' => $order->reference, 'err' => $e->getMessage()]);
            $result = PaymentResult::failed($e->getMessage());
        }

        $this->applyResultToTransaction($transaction, $result);

        return [
            'order'       => $order,
            'transaction' => $transaction->refresh(),
            'result'      => $result,
        ];
    }

    /**
     * Reconcile a transaction status (called by webhook or by polling).
     */
    public function reconcile(Transaction $transaction): Transaction
    {
        if ($transaction->status === PaymentStatus::PAID || ! $transaction->provider_reference) {
            return $transaction;
        }

        $gateway = $this->paymentManager->gateway($transaction->gateway);
        $result  = $gateway->verify($transaction->provider_reference);

        $this->applyResultToTransaction($transaction, $result);

        return $transaction->refresh();
    }

    public function applyResultToTransaction(Transaction $tx, PaymentResult $result): void
    {
        DB::transaction(function () use ($tx, $result) {
            $tx->update([
                'status'             => $result->status->value,
                'provider_reference' => $result->providerReference ?? $tx->provider_reference,
                'gateway_response'   => $result->rawResponse,
                'failure_reason'     => $result->isFailed() ? $result->message : null,
                'processed_at'       => $result->status !== PaymentStatus::PENDING ? now() : null,
            ]);

            if ($result->isSuccessful()) {
                $this->orderService->markAsPaid($tx->order);
            } elseif ($result->isFailed()) {
                $this->orderService->markAsFailed($tx->order, $result->message);
            }
        });
    }
}
