<?php

return [
    'prefix' => 'admin',
    'models' => [
        'admin' => GiorgioSpa\Models\Admin::class,
        'permission' => GiorgioSpa\Models\Permission::class,
        'role' => GiorgioSpa\Models\Role::class,
    ],
    'tencent' => [
        'cos' => [
            //cos
            'cos_secret_id' => env('COS_SECRET_ID', ''),
            'cos_secret_key' => env('COS_SECRET_KEY', ''),
            'cos_region' => env('COS_REGION', 'ap-nanjing'),
            'cos_scheme' => env('COS_SCHEME', 'https'),
            'cos_bucket' => env('COS_BUCKET', ''),
            'cos_appId' => env('COS_APP_ID', ''),
            'cos_timeout' => env('COS_TIMEOUT', 60),
            'cos_connect_timeout' => env('COS_CONNECT_TIMEOUT', 60),
            'cos_pre_domain' => env('COS_DOMAIN', ''),
        ],
    ],

];