<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Payments\PaymentManager;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    public function __construct(
        protected PaymentManager $paymentManager,
        protected PaymentService $paymentService,
    ) {}

    public function handle(string $gateway, Request $request)
    {
        $driver = $this->paymentManager->gateway($gateway);
        $result = $driver->handleWebhook($request);

        if (! $result->providerReference) {
            return response()->json(['ok' => false, 'reason' => 'no_reference'], 422);
        }

        $tx = Transaction::where('provider_reference', $result->providerReference)->first();
        if (! $tx) {
            return response()->json(['ok' => false, 'reason' => 'tx_not_found'], 404);
        }

        $this->paymentService->applyResultToTransaction($tx, $result);

        return response()->json(['ok' => true]);
    }
}
