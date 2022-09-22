<?php

namespace GiorgioSpa\Http\Controllers\Admin\System;

use GiorgioSpa\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $permissions = Permission::query()
            ->filter($request->all())
            ->orderBy('name')
            ->get();
        return $this->success($permissions);
    }

    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        if(admin()->isSuper()){
            return $this->success(['*']);
        }

        $permissions = admin()->getAllPermissions();
        return $this->success($permissions->pluck('name'));
    }
}
