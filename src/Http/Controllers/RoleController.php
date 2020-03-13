<?php

namespace GiorgioSpa\Http\Controllers;

use GiorgioSpa\Http\Requests\RoleRequest;
use GiorgioSpa\Models\Admin;
use GiorgioSpa\Models\Role;
use GiorgioSpa\Models\RoleElement;
use GiorgioSpa\Models\RoleMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function lists()
    {
        $page = request('limit', 20);
        $roles = Role::query()->name()->alias()->paginate($page);
        return $this->success($roles);
    }

    public function all()
    {
        $roles = Role::query()->get();
        return $this->success($roles);
    }

    public function auth($id)
    {
        Role::query()->findOrFail($id);
        $menus = RoleMenu::query()->select('permission_id')->where('role_id', $id)->get()->toArray();
        $elements = RoleElement::query()->select('element_id')->where('role_id', $id)->get()->toArray();
        return $this->success([
            'menus' => array_column($menus, 'permission_id'),
            'elements' => array_column($elements, 'element_id')
        ]);
    }

    public function setAuth($id)
    {
        Role::query()->findOrFail($id);
        $menus = request('menus');
        $elements = request('elements');

        try {
            DB::transaction(function () use ($id, $menus, $elements) {
                # 清空原数据
                RoleMenu::query()->where('role_id', $id)->delete();
                RoleElement::query()->where('role_id', $id)->delete();

                # 菜单整理
                $menu_filter = ['role_id', 'permission_id'];
                array_walk($menus, function (&$value) use ($id, $menu_filter) {
                    $value = array_combine($menu_filter, [$id, $value]);
                });

                # 资源整理
                $element_filter = ['role_id', 'element_id'];
                array_walk($elements, function (&$value) use ($id, $element_filter) {
                    $value = array_combine($element_filter, [$id, $value]);
                });
                RoleMenu::query()->insert($menus);
                RoleElement::query()->insert($elements);
            });

            return $this->success();
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->error('系统错误。');
        }
    }

    public function detail($id)
    {
        $detail = Role::query()->findOrFail($id);
        return $this->success($detail);
    }

    public function update($id, RoleRequest $request)
    {
        $role = Role::query()->findOrFail($id);
        $role->update(request_intersect([
            'name', 'alias'
        ]));
        return $this->success();
    }

    public function create(RoleRequest $request)
    {
        $role = new Role();
        $role->query()->create(request_intersect([
            'name', 'alias'
        ]));
        return $this->success();
    }

    public function delete($id)
    {
        $role = Role::query()->findOrFail($id);
        if ($id == 1) return $this->error('该角色内置，不可删除');
        if(Admin::query()->where('role_id', $id)->count()){
            return $this->error('请先删除该角色下的用户');
        }
        # 清除该角色所属权限
        RoleMenu::query()->where('role_id', $id)->delete();
        RoleElement::query()->where('role_id', $id)->delete();
        $role->delete();
        return $this->success();
    }
}
