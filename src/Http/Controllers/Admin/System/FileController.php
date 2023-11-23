<?php

namespace GiorgioSpa\Http\Controllers\Admin\System;

use GiorgioSpa\Http\Controllers\Controller;
use GiorgioSpa\Services\Tencent\CosService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    //允许上传的文件类型
    protected array $extensionWhiteList = [
        'jpg',
        'jpeg',
        'png',
        'bmp',
        'pdf',
        'ico',
        'xls',
        'xlsx',
        'zip',
        'tar',
        'gz',
        '7z',
        'rar',
        'doc',
        'docx',
        'txt',
        'csv',
        'mp4',
        'avi',
        'rmvb',
        'flv',
        'mov',
        'wmv',
        'pem'
    ];

    /**
     *
     */
    public function upload(Request $request,$arr = 0): \Illuminate\Http\JsonResponse | array
    {
        ini_set('memory_limit','1024M');
        abort_if(!$request->file('file'), 400,'文件必传');
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        if (!in_array(strtolower($extension), $this->extensionWhiteList)) {
            abort(400,'文件格式非法');
        }
        $size = $file->getSize();
        $maxSize = config('filesystems.max_upload_size');

        abort_if($size > $maxSize, 400,'超出最大文件上传限制,请压缩后重试');

        //存储在腾讯云
        $path = CosService::save($file->getContent(), $fileName);


        return $this->success([
            'path' => $path,

        ]);
    }
}
