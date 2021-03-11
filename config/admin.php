<?php

use Giorgio\Models\Admin;

return [
    'name' => config('APP_NAME', 'Giorgio-spa'),
    'prefix' => 'admin',
    'routes' => [ // 路由
        [
            'namespace' => 'Giorgio\Http\Controllers',
            'name' => 'admin'
        ]
    ],
    'auth' => [
        'guards' => [
            'admin' => [
                'driver' => 'session',
                'provider' => 'admins',
            ],
        ],
        'providers' => [
            'admins' => [
                'driver' => 'eloquent',
                'model' => Admin::class,
                'username' => 'email',
                'extra' => [

                ]
            ],
        ],
    ],
];
