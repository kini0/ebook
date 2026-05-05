<?php

namespace App\Jobs;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public int $orderId) {}

    public function handle(): void
    {
        $order = Order::with(['items.ebook', 'user', 'invoice'])->find($this->orderId);
        if (! $order || ! $order->user) {
            return;
        }

        Mail::to($order->billing_email)
            ->cc($order->user->email)
            ->send(new OrderConfirmationMail($order));
    }
}
