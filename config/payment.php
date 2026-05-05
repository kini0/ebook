<?php

/*
|--------------------------------------------------------------------------
| Payment Configuration
|--------------------------------------------------------------------------
|
| Centralized configuration for the payment gateway abstraction.
| Each driver listed here is bound by App\Providers\PaymentServiceProvider
| and resolved through App\Payments\PaymentManager. Adding a new gateway
| only requires creating a driver class implementing PaymentGatewayInterface
| and registering it here.
|
*/

return [

    'default' => env('PAYMENT_DEFAULT', 'card'),

    'currency' => [
        'code'   => env('CURRENCY', 'XOF'),
        'symbol' => env('CURRENCY_SYMBOL', 'FCFA'),
    ],

    'drivers' => [

        'card' => [
            'driver'         => App\Payments\Drivers\CardDriver::class,
            'label'          => 'Carte bancaire',
            'icon'           => 'credit-card',
            'enabled'        => true,
            'provider'       => env('CARD_DRIVER', 'stripe'),
            'stripe' => [
                'key'             => env('STRIPE_KEY'),
                'secret'          => env('STRIPE_SECRET'),
                'webhook_secret'  => env('STRIPE_WEBHOOK_SECRET'),
            ],
            'cinetpay' => [
                'api_key'    => env('CINETPAY_API_KEY'),
                'site_id'    => env('CINETPAY_SITE_ID'),
                'secret_key' => env('CINETPAY_SECRET_KEY'),
                'notify_url' => env('CINETPAY_NOTIFY_URL'),
            ],
        ],

        'mobile_money' => [
            'driver'   => App\Payments\Drivers\MobileMoneyDriver::class,
            'label'    => 'Mobile Money',
            'icon'     => 'smartphone',
            'enabled'  => true,
            'provider' => env('MOBILEMONEY_DRIVER', 'cinetpay'),
            'operators' => [
                'orange' => [
                    'label'         => 'Orange Money',
                    'client_id'     => env('ORANGE_MONEY_CLIENT_ID'),
                    'client_secret' => env('ORANGE_MONEY_CLIENT_SECRET'),
                    'merchant_key'  => env('ORANGE_MONEY_MERCHANT_KEY'),
                ],
                'mtn' => [
                    'label'            => 'MTN MoMo',
                    'user_id'          => env('MTN_MOMO_USER_ID'),
                    'api_key'          => env('MTN_MOMO_API_KEY'),
                    'subscription_key' => env('MTN_MOMO_SUBSCRIPTION_KEY'),
                ],
                'wave' => [
                    'label'   => 'Wave',
                    'api_key' => env('WAVE_API_KEY'),
                    'secret'  => env('WAVE_SECRET'),
                ],
            ],
        ],

        'bank_transfer' => [
            'driver'      => App\Payments\Drivers\BankTransferDriver::class,
            'label'       => 'Virement bancaire',
            'icon'        => 'bank',
            'enabled'     => true,
            'bank_name'   => env('BANK_NAME', 'Ecobank'),
            'iban'        => env('BANK_IBAN'),
            'rib'         => env('BANK_RIB'),
            'beneficiary' => env('BANK_BENEFICIARY'),
        ],

    ],

    'downloads' => [
        'url_ttl_seconds' => (int) env('DOWNLOAD_URL_TTL', 900),
        'max_per_order'   => (int) env('DOWNLOAD_MAX_PER_ORDER', 10),
    ],

];
