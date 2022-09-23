<?php

namespace GiorgioSpa\Http\Controllers\Admin\System;

use GiorgioSpa\Http\Controllers\Controller;
use GiorgioSpa\Services\ChuangLan\SmsService;
use GiorgioSpa\Services\ModelRegister;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SmsCodeController extends Controller
{

    public function store(Request $request): JsonResponse
    {
        $data = $request->all();

        $this->validate($request, [
            'account' => 'required'
        ], [
            'account.required' => '账号必填'
        ]);

        if(isset($data['is_login'])){
            $admin = app(ModelRegister::class)->getAdminClass()::query()->where('phone','=',$data['account'])
                ->orWhere('name','=',$data['account'])
                ->first();

            if(empty($admin)){
                abort(404, '账户不存在');
            }
        }

        SmsService::sendSmsCode($data['account']);
        return $this->success();
    }
}
