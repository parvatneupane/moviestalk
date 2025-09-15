<?php

return [
    'defaults' => [
        'guard' => 'web',  // 'web' guard is fine for both users and admins
        'passwords' => 'users',  // The default password broker
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',  // Using the 'users' provider for both users and admins
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,  // User model is used for both users and admins
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
