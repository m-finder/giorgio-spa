<?php

namespace GiorgioSpa\Http\Controllers\Admin\System;

use GiorgioSpa\Http\Controllers\Controller;
use GiorgioSpa\Models\PersonalAccessToken;
use GiorgioSpa\Services\ChuangLan\SmsService;
use GiorgioSpa\Services\ModelRegister;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function login(Request $request): JsonResponse
    {
        $data = $request->all();
        $rule = [
            'account' => 'required',
            'sms_code' => 'required_if:is_password,false',
            'password' => 'required_if:is_password,true'
        ];

        if (config('app.env') != 'local') {
            $rule['captcha_key'] = 'required';
            $rule['captcha'] = 'required|captcha_api:' . $data['captcha_key'];
        }

        $this->validate($request, $rule, [
            'account.required' => '账号必填',
            'captcha_key.required' => '验证码key必填',
            'captcha.required' => '图形验证码必填',
            'captcha.captcha_api' => '图形验证码错误',
            'sms_code.required_if' => '短信验证码错误',
            'password.required_if' => '密码错误',
        ]);

        $admin = $this->validateAdmin($request);

        if($request->get('is_password') === true){
            if(!password_verify($request->get('password'), $admin->getAttribute('password'))){
                abort(400, '密码错误');
            }
        }

        auth()->login($admin);

        abort_if(admin()->isDisabled(), 403, '账号已禁用,请联系管理员解禁');

        if ($request->get('is_password') !== true && !SmsService::validateSmsCode(admin()->getPhone(), $data['sms_code'])) {
            abort(400, '短信验证码错误');
        }

        return $this->loginSucceeded();

    }

    public function loginSucceeded(): JsonResponse
    {
        PersonalAccessToken::handleOldToken();
        $token = admin()->createToken('sc-token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'admin' => admin(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success();
    }

    protected function validateAdmin(Request $request)
    {
        $account = $request->get('account');
        $admin = app(ModelRegister::class)->getAdminClass()::query()->where(function ($query) use($account){
            return $query->where('phone', '=', $account)->orWhere('name', '=', $account);
        })->first();

        abort_if(empty($admin), 404, '账户不存在');
        return $admin;
    }
}
