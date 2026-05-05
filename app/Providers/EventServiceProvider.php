<?php

namespace App\Providers;

use App\Events\OrderPlaced;
use App\Events\PaymentFailed;
use App\Events\PaymentSucceeded;
use App\Listeners\GenerateInvoiceForOrder;
use App\Listeners\NotifyAdminPaymentFailed;
use App\Listeners\SendOrderConfirmation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /** @var array<class-string, array<int, class-string>> */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        OrderPlaced::class => [
            // marker event
        ],

        PaymentSucceeded::class => [
            GenerateInvoiceForOrder::class,
            SendOrderConfirmation::class,
        ],

        PaymentFailed::class => [
            NotifyAdminPaymentFailed::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
