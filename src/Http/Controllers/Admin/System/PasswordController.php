<?php

namespace GiorgioSpa\Http\Controllers\Admin\System;

use GiorgioSpa\Http\Controllers\Controller;
use GiorgioSpa\Services\ModelRegister;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function update(Request $request, int $id): JsonResponse
    {
        $admin = app(ModelRegister::class)->getAdminClass()->query()->find($id);
        $data = $request->all();
        $this->validate($request, [
            'origin_password' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ], [
            'origin_password.required' => '原密码必填',
            'password.required' => '密码必填',
            'confirm_password.required' => '确认密码必填',
            'confirm_password.same' => '两次密码不一致',
        ]);

        $password = $admin->getAttribute('password');
        if (! password_verify($data['origin_password'], $password)) {
            abort(400, '原密码错误');
        }
        $admin->setAttribute('password', bcrypt($data['password']));
        $admin->save();

        return $this->success();
    }

    public function reset(Request $request, int $id): JsonResponse
    {
        $admin = app(ModelRegister::class)->getAdminClass()->query()->find($id);
        $password = Str::random(8);
        $admin->setAttribute('password', bcrypt($password));
        $admin->save();

        return $this->success(['password' => $password]);
    }
}
