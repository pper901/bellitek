<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'uploadcare' => [
        'public' => env('UPLOADCARE_PUBLIC_KEY'),
        'secret' => env('UPLOADCARE_SECRET_KEY'),
    ],
    // ...
    'shipbubble' => [
        'base_url' => 'https://api.shipbubble.com/v1/',
        'key' => env('SHIPBUBBLE_API_KEY'),
    ],
    // ...
    'paystack' => [
        'base_url' => 'https://api.paystack.co/',
        'key' => env('PAYSTACK_SECRET'),
    ],

    'generalclass' => [
        'url' => env('GENERALCLASS_URL'),
        'websocket_url' => env('GENERALCLASS_WEBSOCKET_URL'),
        'internal_api_key' => 'aErH8eYPLwxtRcRA9bEabHxiJJwjzri1aLy17IjNtX3kbP3PK6nVjpNHC3vk8pip',
        'java_container' => env('GENERALCLASS_JAVA_CONTAINER', 'generalclass-server'),
        'laravel_container' => env('BELLITEK_LARAVEL_CONTAINER', 'bellitek-app'),
    ],
];
