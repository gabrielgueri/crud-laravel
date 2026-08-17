<?php

return [
    'default' => env('DB_CONNECTION', 'pgsql'),
    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', 5432),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'laravel'),
            'password' => env('DB_PASSWORD', 'secret'),
        ],
    ],
];
