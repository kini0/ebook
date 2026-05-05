<?php

namespace App\Listeners;

use App\Events\PaymentFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NotifyAdminPaymentFailed implements ShouldQueue
{
    public function handle(PaymentFailed $event): void
    {
        // In production: dispatch a Mailable to admin or push to Slack/Telegram.
        Log::warning('Paiement échoué', [
            'order'  => $event->order->reference,
            'reason' => $event->reason,
        ]);
    }
}
