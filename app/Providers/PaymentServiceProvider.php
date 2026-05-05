<?php

namespace App\Providers;

use App\Payments\PaymentManager;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentManager::class, function ($app) {
            return new PaymentManager($app, config('payment'));
        });

        $this->app->alias(PaymentManager::class, 'payment.manager');
    }

    public function boot(): void
    {
        //
    }
}
