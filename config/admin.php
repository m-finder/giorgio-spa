<?php

return [
    'uri' => 'admin',
    'auth' => [
        'guards' => [
            'admin-api' => [
                'driver' => 'token',
                'provider' => 'admins',
                'hash' => false,
            ],
        ],
        'providers' => [
            'admins' => [
                'driver' => 'eloquent',
                'model' => GiorgioSpa\Models\Admin::class,
            ],
        ],
    ],
];