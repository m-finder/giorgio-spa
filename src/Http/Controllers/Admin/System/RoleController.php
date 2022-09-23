<?php

namespace GiorgioSpa\Http\Controllers\Admin\System;

use GiorgioSpa\Http\Controllers\Controller;
use GiorgioSpa\Models\ModelHasRole;
use GiorgioSpa\Models\Role;
use GiorgioSpa\Models\RoleHasPermission;
use GiorgioSpa\Services\ModelRegister;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $res = app(ModelRegister::class)->getRoleClass()::query()
            ->with('permissions:id,name')
            ->filter($request->all())
            ->orderByDesc('id')
            ->paginate($request->get('limit'));
        return $this->success($res);
    }

    public function list(Request $request): JsonResponse
    {
        $res = app(ModelRegister::class)->getRoleClass()::query()
            ->filter($request->all())
            ->orderByDesc('id')
            ->select(['id', 'name'])
            ->paginate($request->get('limit'));
        return $this->success($res);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->all();

        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('roles')->where(function ($query) {
                    $query->where('deleted_at', '=', null);
                }),
                'max:255'
            ],
            'brief' => 'nullable|max:255',
        ], [
            'name.required' => '角色名称必填',
            'name.unique' => '角色名已存在',
            'brief.max' => '角色描述长度超限',
        ]);

        $data['guard_name'] = 'custom';
        app(ModelRegister::class)->getRoleClass()::query()->create($data);
        return $this->success();
    }

    public function update(Request $request, Role $role): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('roles')->where(function ($query) {
                    $query->where('deleted_at', '=', null);
                })->ignore($role->getKey()),
                'max:255'
            ],
            'brief' => 'nullable|max:255',
        ], [
            'name.required' => '角色名必填',
            'name.unique' => '角色名已存在',
            'name.max' => '角色名长度超限',
            'brief.max' => '角色描述长度超限',
        ]);

        $defaultRoleIds = app(ModelRegister::class)->getRoleClass()::query()
            ->where([
                'is_super' => 1,
            ])->get(['id'])->pluck('id')->toArray();
        if (in_array($role->getKey(), $defaultRoleIds)) {
            abort(403, $role->getName() . '为默认角色,禁止修改');
        }
        $role->fill($data);
        $role->save();
        return $this->success();
    }


    public function destroy(Role $role): JsonResponse
    {
        $defaultRoleIds = app(ModelRegister::class)->getRoleClass()::query()->where([
            'is_super' => 1
        ])->get(['id'])->pluck('id')->toArray();

        if (in_array($role->getKey(), $defaultRoleIds)) {
            abort(400, $role->getName() . '为默认角色禁止删除');
        }
        ModelHasRole::query()->where('role_id', '=', $role->getKey())->delete();
        RoleHasPermission::query()->where('role_id', '=', $role->getKey())->delete();
        $role->delete();
        return $this->success();
    }

    public function auth(Request $request, Role $role): JsonResponse
    {

        $data = $request->all();
        $this->validate($request, [
            'permissions' => 'array'
        ], [
            'permissions.array' => '角色权限类型错误',
        ]);
        $oldIds = $role->getAllPermissions()->pluck('id')->toArray();
        $newIds = app(ModelRegister::class)->getPermissionClass()::query()->whereIn('name', $data['permissions'])->get()->pluck('id')->toArray();

        $old = array_diff($oldIds, $newIds);
        $new = array_diff($newIds, $oldIds);
        $role->givePermissionTo($new);
        RoleHasPermission::query()->where('role_id', '=', $role->getKey())
            ->whereIn('permission_id', $old)
            ->delete();
        return $this->success();
    }
}
