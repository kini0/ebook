<?php

namespace App\Exceptions;

/**
 * Stub kept for backward-compatibility.
 * In Laravel 11, exception handling is configured in bootstrap/app.php
 * via $exceptions->render(...) callbacks.
 *
 * @deprecated Use bootstrap/app.php (Laravel 11+).
 */
class Handler extends \Illuminate\Foundation\Exceptions\Handler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
}
