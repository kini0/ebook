<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name / Environment
    |--------------------------------------------------------------------------
    */
    'name'             => env('APP_NAME', 'EbookSaaS'),
    'env'              => env('APP_ENV', 'production'),
    'debug'            => (bool) env('APP_DEBUG', false),
    'url'              => env('APP_URL', 'http://localhost'),
    'asset_url'        => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Timezone & Locale (FR)
    |--------------------------------------------------------------------------
    */
    'timezone'         => env('APP_TIMEZONE', 'Africa/Abidjan'),
    'locale'           => env('APP_LOCALE', 'fr'),
    'fallback_locale'  => env('APP_FALLBACK_LOCALE', 'fr'),
    'faker_locale'     => env('APP_FAKER_LOCALE', 'fr_FR'),

    /*
    |--------------------------------------------------------------------------
    | Encryption / Maintenance
    |--------------------------------------------------------------------------
    */
    'cipher'           => 'AES-256-CBC',
    'key'              => env('APP_KEY'),
    'previous_keys'    => array_filter(explode(',', (string) env('APP_PREVIOUS_KEYS', ''))),
    'maintenance'      => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store'  => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | NOTE — Laravel 11
    |--------------------------------------------------------------------------
    | Service providers are declared in bootstrap/providers.php.
    | The "providers" and "aliases" arrays are no longer used here.
    */

];
