<?php

namespace GiorgioSpa\Http\Controllers;

use Carbon\Carbon;
use GiorgioSpa\Http\Requests\AdminRequest;
use GiorgioSpa\Models\Admin;
use GiorgioSpa\Models\Code;
use GiorgioSpa\Models\Element;
use GiorgioSpa\Models\Menu;
use GiorgioSpa\Models\RoleElement;
use GiorgioSpa\Models\RoleMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    /**
     * 验证码过期时间
     */
    const CODE_EXPIRED_TIME = 30;

    public function lists()
    {
        $page = request('limit', 20);
        $users = Admin::query()->name(request('name'))->email(request('email'))->with('role')->paginate($page);
        return $this->success($users);
    }

    public function adminAuth()
    {
        $admin = Auth::user();

        $role_menus = RoleMenu::query()->select('permission_id')->where('role_id', $admin->role_id)->get()->toArray();
        $role_elements = RoleElement::query()->select('element_id')->where('role_id', $admin->role_id)->get()->toArray();

        $menus = Menu::query()->whereIn('id', array_column($role_menus, 'permission_id'))->get();
        $elements = Element::query()->whereIn('id', array_column($role_elements, 'element_id'))->get();

        return $this->success([
            'menus' => make_tree($menus->toArray()),
            'elements' => $elements
        ]);
    }

    public function detail($id)
    {
        $detail = Admin::query()->findOrFail($id);
        return $this->success($detail);
    }

    public function update(AdminRequest $request, $id)
    {
        $admin = Admin::query()->findOrFail($id);
        $data = request_intersect([
            'role_id', 'name', 'email'
        ]);
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        $admin->update($data);
        return $this->success();
    }

    public function create(AdminRequest $request)
    {
        $admin = new Admin();
        $data = request_intersect([
            'role_id', 'name', 'email', 'password'
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['api_token'] = (string)Str::uuid();
        $data['avatar'] = 'images/avatar.jpg';
        $admin->query()->create($data);
        return $this->success();
    }

    public function delete($id)
    {
        $admin = Admin::query()->findOrFail($id);
        if ($id == 1) return $this->error('该用户内置，不可删除。');
        $admin->delete();
        return $this->success();
    }

    /**
     * 忘记密码
     */
    public function resetPasswordByMail()
    {
        $data = request_intersect([
            'email', 'code', 'password'
        ]);

        $code = Code::query()->where('email', $data['email'])
            ->where('is_used', 0)
            ->orderBy('id', 'desc')->first();
        if ($this->isCodeExpired($code)) {
            return $this->error('无效的验证码。');
        }

        if (is_null($admin = Admin::query()->where('email', $data['email'])->first())) {
            return $this->error('无效的邮箱。');
        }

        $admin->update(['password' => Hash::make($data['password'])]);
        $code->update(['is_used' => 1]);
        return $this->success();
    }

    public function isCodeExpired($code = null)
    {
        return is_null($code) || $code->created_at < Carbon::now()->subMinute(self::CODE_EXPIRED_TIME);
    }


    public function resetPassword()
    {
        $admin = Auth::user();
        $data = request_intersect([
            'original_password', 'password'
        ]);
        if (!Hash::check($data['original_password'], $admin->password)) {
            return $this->error('原始密码错误。');
        }
        $admin->update(['password' => Hash::make($data['password'])]);
        return $this->success();
    }

    public function detailByAuth()
    {
        return $this->success(Auth::user());
    }

    public function avatarUpload(Request $request)
    {
        $path = $request->file('avatar')->store('public/avatars');
        return $this->success(Storage::url($path));
    }

    public function resetInfo()
    {
        $data = request_intersect([
            'name', 'avatar'
        ]);
        $admin = Auth::user();
        $admin->update($data);
        return $this->success();
    }
}
