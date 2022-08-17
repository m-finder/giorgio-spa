<?php
/**
 * 腾讯云配置
 */
return [

    //ocr
	'secret_id' => env('TENCENT_SECRET_ID',''),
	'secret_key' => env('TENCENT_SECRET_KEY',''),

    //cos
    'cos_secret_id' => env('COSV5_SECRET_ID', ''),
    'cos_secret_key' => env('COSV5_SECRET_KEY', ''),
    'cos_region' => env('COSV5_REGION', 'ap-nanjing'),
    'cos_scheme' => env('COSV5_SCHEME', 'https'),
    'cos_bucket' => env('COSV5_BUCKET', ''),
    'cos_appId' => env('COSV5_APP_ID', ''),
    'cos_timeout' => env('COSV5_TIMEOUT', 60),
    'cos_connect_timeout' => env('COSV5_CONNECT_TIMEOUT', 60),
    'cos_pre_domain' => env('COSV5_DOMAIN', ''),
];


