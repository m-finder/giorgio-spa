<?php

namespace GiorgioSpa\Services\Tencent;

use Qcloud\Cos\Client;

class CosService
{

    public static function save($file,$fileName): string
    {
        $cosClient = new Client(
            [
                'region' => config('tencent_cloud.cos_region'),
                'schema' => config('tencent_cloud.cos_scheme'), //协议头部，默认为http
                'credentials' => [
                    'secretId' => config('tencent_cloud.cos_secret_id'),
                    'secretKey' => config('tencent_cloud.cos_secret_key')
                ]
            ]
        );

        $result = $cosClient->upload(
            config('tencent_cloud.cos_bucket'), //存储桶名称，由BucketName-Appid 组成，可以在COS控制台查看 https://console.cloud.tencent.com/cos5/bucket
            $fileName, //此处的 key 为对象键
            $file
        );
        return config('tencent_cloud.cos_scheme').'://'.$result['Location'];
    }
}
