<?php

namespace App\Listeners;

use App\Events\PaymentSucceeded;
use App\Jobs\GenerateInvoicePdf;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateInvoiceForOrder implements ShouldQueue
{
    public function handle(PaymentSucceeded $event): void
    {
        GenerateInvoicePdf::dispatch($event->order->id);
    }
}
