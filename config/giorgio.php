<?php

return [
    'prefix' => 'admin',
    'auth' => [
        'guards' => [
            'custom' => [
                'driver' => 'session',
                'provider' => 'custom',
            ],
        ],
        'providers' => [
            'custom' => [
                'driver' => 'custom',
                'model' => GiorgioSpa\Models\Admin::class,
            ]
        ],
    ],
];