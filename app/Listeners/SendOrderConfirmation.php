<?php

namespace App\Listeners;

use App\Events\PaymentSucceeded;
use App\Jobs\SendOrderConfirmationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderConfirmation implements ShouldQueue
{
    public function handle(PaymentSucceeded $event): void
    {
        SendOrderConfirmationEmail::dispatch($event->order->id)
            ->delay(now()->addSeconds(5)); // give the invoice job a moment
    }
}
