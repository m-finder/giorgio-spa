<?php

namespace GiorgioSpa\Services\Tencent;

use Qcloud\Cos\Client;

class CosService
{
    public static function save($file, $fileName): string
    {
        $cosClient = new Client(
            [
                'region' => config('giorgio.tencent.cos.region'),
                'schema' => config('giorgio.tencent.cos.scheme'), //协议头部，默认为http
                'credentials' => [
                    'secretId' => config('giorgio.tencent.cos.secret_id'),
                    'secretKey' => config('giorgio.tencent.cos.secret_key'),
                ],
            ]
        );

        // 存储桶名称，由BucketName-Appid 组成，可以在COS控制台查看 https://console.cloud.tencent.com/cos5/bucket
        // 此处的 key 为对象键
        $result = $cosClient->upload(
            config('giorgio.tencent.cos.bucket'),
            $fileName,
            $file
        );

        return config('giorgio.tencent.cos.scheme').'://'.$result['Location'];
    }
}
