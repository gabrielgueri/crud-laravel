<?php

return [
    'default' => env('CACHE_DRIVER', 'redis'),
    'redis' => [
        'driver' => 'redis',
        'connection' => 'cache',
    ],
];
