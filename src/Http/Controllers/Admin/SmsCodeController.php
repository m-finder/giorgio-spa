<?php

namespace GiorgioSpa\Http\Controllers\Admin;

use GiorgioSpa\Http\Controllers\Controller;
use GiorgioSpa\Models\Admin;
use GiorgioSpa\Services\Tencent\SmsService;
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
            $admin = Admin::query()->where('phone','=',$data['account'])
                ->orWhere('name','=',$data['account'])
                ->first();

            if(empty($admin)){
                abort(400,'账号不存在');
            }
        }

        SmsService::sendSmsCode($data['account']);
        return $this->success();
    }
}
