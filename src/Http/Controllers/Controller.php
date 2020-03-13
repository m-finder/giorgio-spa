<?php

namespace GiorgioSpa\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    protected function success($data = null)
    {
        return response()->json($data, Response::HTTP_OK);
    }

    protected function error($msg = null)
    {
        return response()->json([
            'message' => $msg ? $msg : '操作失败'
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
