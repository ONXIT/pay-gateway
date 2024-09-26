<?php

return [

    'default_driver' => env('PAYMENT_DRIVER', 'imoje'),

    'drivers' => [

        'imoje' => [
            'provider' => \Qionar\PayGateway\Strategies\ImojeProvider::class,

            'base_url' => env('PAYMENT_ING_BASE_URL', 'https://sandbox.api.imoje.pl/v1/merchant/'),
            'service_id' => env('PAYMENT_ING_SERVICE_ID', ''),
            'merchant_id' => env('PAYMENT_ING_MERCHANT_ID', ''),
            'merchant_key' => env('PAYMENT_ING_MERCHANT_KEY', ''),
            'auth_token' => env('PAYMENT_ING_AUTH_TOKEN', ''),
            'default_language' => env('PAYMENT_ING_DEFAULT_LANGUAGE', 'pl'),
            'default_currency' => env('PAYMENT_ING_CURRENCY', 'PLN'),

            'webhook' => [
                'enabled' => env('PAYMENT_ING_WEBHOOK_ENABLED', true),
                'uri' => env('PAYMENT_ING_WEBHOOK_URI', '/webhooks/payments/imoje'),
                'controller' => \Qionar\PayGateway\Controllers\ImojeWebhookController::class,
                'method' => 'POST',
                'trusted_ip_ranges' => [
                    '5.196.116.32/28',
                    '51.195.95.0/28',
                    '54.37.185.64/28',
                    '54.37.185.80/28',
                    '147.135.151.16/28',
                ]
            ],
        ]

    ]

];
