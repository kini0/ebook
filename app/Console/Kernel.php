<?php

namespace App\Console;

/**
 * Stub kept for backward-compatibility.
 * In Laravel 11, scheduled tasks live in routes/console.php.
 *
 * @deprecated Use routes/console.php (Laravel 11+).
 */
class Kernel extends \Illuminate\Foundation\Console\Kernel
{
    protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule): void
    {
        //
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
