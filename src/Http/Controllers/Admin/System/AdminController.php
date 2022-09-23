<?php

namespace GiorgioSpa\Http\Controllers\Admin\System;

use GiorgioSpa\Http\Controllers\Controller;
use GiorgioSpa\Models\Admin;
use GiorgioSpa\Models\Role;
use GiorgioSpa\Rules\NumericLength;
use GiorgioSpa\Services\ModelRegister;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

	public function index(Request $request): JsonResponse
	{
		$res = app(ModelRegister::class)->getAdminClass()::query()->with('roles')->filter($request->all())
			->orderByDesc('id')
			->paginate($request->get('limit'));
		return $this->success($res);
	}

	public function list(Request $request): JsonResponse
	{
		$res = app(ModelRegister::class)->getAdminClass()::query()->with('roles')
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
				Rule::unique('admins')->where(function ($query) {
					$query->where('deleted_at', '=', null);
				}),
			],
			'email' => [
				'nullable',
				'email'
			],
			'phone' => [
				'required',
				Rule::unique('admins')->where(function ($query) {
					$query->where('deleted_at', '=', null);
				}),
				'numeric',
				new NumericLength(11, '手机号')
			],
			'role_ids' => 'required|array'
		], [
			'name.required' => '用户名必填',
			'name.unique' => '用户名已注册',
			'email.unique' => '邮箱已注册',
			'email.email' => '邮箱格式错误',
			'phone.required' => '手机号必填',
			'phone.unique' => '手机号已注册',
			'role_ids.required' => '角色id必填',
			'role_ids.array' => '角色id类型错误'
		]);

        $defaultRoleIds = app(ModelRegister::class)->getRoleClass()::query()->where('is_super', '=', 1)->get(['id'])->pluck('id')->toArray();
		$intersect = array_intersect($defaultRoleIds, $data['role_ids']);
		if (!empty($intersect)) {
			abort(403, '禁止建立系统默认角色');
		}
		$countRoleId = app(ModelRegister::class)->getRoleClass()::query()->whereIn('id', $data['role_ids'])->count('id');
		if (empty($countRoleId)) {
			abort(404, '未查询到角色id所属角色');
		}
		if (count($data['role_ids']) != $countRoleId) {
			abort(400, '存在非法角色');
		}

		$admin = app(ModelRegister::class)->getAdminClass()::create($data);
		$admin->syncRoles($data['role_ids']);

		return $this->success();
	}

	public function update(Request $request, Admin $admin): JsonResponse
	{
		$data = $request->all();
		$this->validate($request, [
			'phone' => [
				'required',
				Rule::unique('admins')->where(function ($query) use ($admin) {
					$query->whereNull('deleted_at');
				})->ignore($admin->getKey()),
			],
			'email' => [
				'required',
				'email'
			],
			'name' => [
				'required',
				Rule::unique('admins')->where(function ($query) {
					$query->whereNull('deleted_at');
				})->ignore($admin->getKey()),
			],
			'role_ids' => 'required|array',
			'status' => [
				'required',
				Rule::in([Admin::STATUS_ENABLED, Admin::STATUS_DISABLED])
			],
		], [
			'phone.required' => '手机号必填',
			'phone.unique' => '手机号已注册',
			'email.required' => '邮箱必填',
			'email.unique' => '邮箱已注册',
			'email.email' => '邮箱格式错误',
			'name.required' => '用户名必填',
			'name.unique' => '用户名已注册',
			'name.max' => '用户名长度超限',
			'role_ids.required' => '角色id必填',
			'role_ids.array' => '角色id类型错误',
			'status.required' => '状态必填',
			'status.in' => '状态类型错误',
		]);

		$origRoleIds = $admin->getRoleIds()->toArray();

        $roleIds = app(ModelRegister::class)->getRoleClass()::query()->where('is_super', '=', 1)
            ->get(['id'])->pluck('id')->toArray();
        $roleIds[] = config('permission.super_admin_role_id.organization');

		$intersect = array_intersect($roleIds, $origRoleIds);
		$diff = array_diff($intersect,$data['role_ids']);

		if (!empty($diff)) {
			abort(403, '当前用户为系统角色,禁止修改角色类型');
		}
		$roleIds = app(ModelRegister::class)->getRoleClass()::query()->whereIn('id', $data['role_ids'])->get(['id'])->toArray();
		if (empty($roleIds)) {
			abort(400, '未查询到角色id所属角色');
		}

		if ($data['status'] == app(ModelRegister::class)->getAdminClass()::STATUS_DISABLED && $admin->isSuper()) {
			abort(400, '超管用户不可禁用');
		}

		$fillData = [
			'phone' => $data['phone'],
			'email' => $data['email'],
			'name' => $data['name'],
			'status' => $data['status'],
			'avatar' => $data['avatar'] ?? null,
			'is_operator' => $data['is_operator'] ?? 0
		];
		$admin->fill($fillData);
		$admin->save();
		$admin->syncRoles($data['role_ids']);

		return $this->success();
	}

	public function destroy(Admin $admin): JsonResponse
	{
		if ($admin->isSuper()) {
			abort(403, '超级管理员禁止删除');
		}
		$admin->delete();
		return $this->success();
	}
}
