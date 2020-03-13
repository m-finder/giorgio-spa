<?php

namespace GiorgioSpa\Http\Controllers;

use GiorgioSpa\Models\Admin;
use GiorgioSpa\Models\Code;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function login()
    {
        $password = request('password');
        $admin = Admin::query()->where('email', request('email'))->first();

        if (is_null($admin)) {
            return $this->error('用户不存在。');
        }
        if (!Hash::check($password, $admin->password)) {
            return $this->error('用户密码错误。');
        }
        return $this->success($admin);
    }


}
