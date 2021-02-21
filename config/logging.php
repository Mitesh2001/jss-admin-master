<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 7,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'info',
        ],

        'papertrail' => [
            'driver'  => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'production_stack' => [
            'driver' => 'stack',
            'channels' => ['daily', 'slack'],
        ],

        'sparkpost' => [
            'driver' => 'daily',
            'path' => storage_path('logs/sparkpost.log'),
            'level' => 'debug',
        ],

        'stripe' => [
            'driver' => 'daily',
            'path' => storage_path('logs/stripe.log'),
            'level' => 'debug',
        ],

        'security' => [
            'driver' => 'daily',
            'path' => storage_path('logs/security_issue.log'),
            'level' => 'debug',
        ],

        'google' => [
            'driver' => 'daily',
            'path' => storage_path('logs/google/google-recaptcha.log'),
            'level' => 'debug',
            'days' => 7,
        ],

        'stripe_failed' => [
            'driver' => 'daily',
            'path' => storage_path('logs/stripe_failed/stripe-failed.log'),
            'level' => 'debug',
            'days' => 7,
        ],

        'renewal_user_agent' => [
            'driver' => 'daily',
            'path' => storage_path('logs/renewal_user_agent/renewal-user-agent.log'),
            'level' => 'debug',
            'days' => 7,
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

    ],

];
