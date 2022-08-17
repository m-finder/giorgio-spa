<?php

namespace GiorgioSpa\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public const HTTP_SUCCESS = 200;

    /**
     *  默认成功返回
     * @param $data
     * @param string $msg
     * @return JsonResponse
     */
    public function success($data = null, string $msg = '操作成功')
    {
        return response()->json([
            'status' => self::HTTP_SUCCESS,
            'data' => $data ?? [],
            'msg' => $msg
        ]);
    }
}
