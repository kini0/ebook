<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Stub kept for backward-compatibility.
 * Broadcasting is opt-in in Laravel 11 (artisan install:broadcasting).
 *
 * Not registered in bootstrap/providers.php — never booted.
 */
class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //
    }
}
