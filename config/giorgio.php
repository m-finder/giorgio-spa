<?php

return [
    'prefix' => 'admin',
    'models' => [
        'admin' => GiorgioSpa\Models\Admin::class,
        'permission' => GiorgioSpa\Models\Permission::class,
        'role' => GiorgioSpa\Models\Role::class,
    ],
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