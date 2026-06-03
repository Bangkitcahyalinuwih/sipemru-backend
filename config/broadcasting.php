<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    */

    'default' => env('BROADCAST_CONNECTION', 'reverb'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    */

    'connections' => [

        'reverb' => [
            'driver' => 'reverb',
            'key' => env('REVERB_APP_KEY'),
            'secret' => env('REVERB_APP_SECRET'),
            'app_id' => env('REVERB_APP_ID'),
            'options' => [
                // Kita kunci langsung ke IP Lokal IPv4 agar tidak tersesat ke IPv6 (localhost)
                'host' => '127.0.0.1',
                'port' => env('REVERB_PORT', 8080),
                'scheme' => env('REVERB_SCHEME', 'http'),
                'useTLS' => env('REVERB_SCHEME', 'http') === 'https',
            ],
            'client_options' => [
                // Guzzle client options
            ],
        ],

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('REVERB_APP_KEY'),     // <--- Ganti ke REVERB agar ekstensi mendeteksinya
            'secret' => env('REVERB_APP_SECRET'), // <--- Ganti ke REVERB
            'app_id' => env('REVERB_APP_ID'),   // <--- Ganti ke REVERB
            'options' => [
                'cluster' => null, // <--- Ubah menjadi null langsung tanpa env()
                'host' => '127.0.0.1',
                'port' => env('REVERB_PORT', 8080),
                'scheme' => env('REVERB_SCHEME', 'http'),
                'encrypted' => false,
                'useTLS' => false,
            ],
            'client_options' => [
                // Guzzle client options
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
