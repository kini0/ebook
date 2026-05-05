<?php

namespace App\Http;

/**
 * Stub kept for backward-compatibility with auto-loaded references.
 * In Laravel 11, the HTTP middleware stack is configured in bootstrap/app.php.
 * This class is no longer instantiated.
 *
 * @deprecated Use bootstrap/app.php (Laravel 11+).
 */
class Kernel extends \Illuminate\Foundation\Http\Kernel
{
    protected $middleware = [];
    protected $middlewareGroups = ['web' => [], 'api' => []];
    protected $routeMiddleware = [];
}
