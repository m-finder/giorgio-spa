<?php

namespace GiorgioSpa\Http\Controllers\Admin\System;

use GiorgioSpa\Http\Controllers\Controller;
use GiorgioSpa\Services\ModelRegister;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $permissions = app(ModelRegister::class)->getPermissionClass()::query()
            ->filter($request->all())
            ->orderBy('name')
            ->get();

        return $this->success($permissions);
    }

    public function list(Request $request): JsonResponse
    {
        if (admin()->isSuper()) {
            return $this->success(['*']);
        }

        $permissions = admin()->getAllPermissions();

        return $this->success($permissions->pluck('name'));
    }
}
