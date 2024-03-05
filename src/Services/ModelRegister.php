<?php

namespace GiorgioSpa\Services;

use GiorgioSpa\Models\Admin;
use GiorgioSpa\Models\Role;
use Spatie\Permission\Contracts\Permission;

class ModelRegister
{
    private string $permissionClass;

    private string $adminClass;

    private string $roleClass;

    public function __construct()
    {
        $this->permissionClass = config('giorgio.models.permission');
        $this->adminClass = config('giorgio.models.admin');
        $this->roleClass = config('giorgio.models.role');
    }

    public function getPermissionClass(): Permission
    {
        return app($this->permissionClass);
    }

    public function getAdminClass(): Admin
    {
        return app($this->adminClass);
    }

    public function getRoleClass(): Role
    {
        return app($this->roleClass);
    }
}
