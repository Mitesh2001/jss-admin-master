<?php

return [
    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
        'bcc' => [
            'name' => env('APP_NAME') . ' Admin',
            'email' => env('SPARKPOST_BCC'),
        ],
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'google' => [
        'recaptcha_site_key' => env('RECAPTCHA_SITE_KEY'),
        'recaptcha_secret_key' => env('RECAPTCHA_SECRET_KEY'),
    ],

];
