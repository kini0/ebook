<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Stub kept for backward-compatibility.
 * In Laravel 11, route registration lives in bootstrap/app.php (->withRouting).
 *
 * Not registered in bootstrap/providers.php and therefore never booted.
 *
 * @deprecated Use bootstrap/app.php (Laravel 11+).
 */
class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/mon-compte';

    public function boot(): void
    {
        //
    }
}
