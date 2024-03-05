<?php

namespace GiorgioSpa\Database\Seeders;

use GiorgioSpa\Services\ModelRegister;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //清空表 然后再填充数据
        app(ModelRegister::class)->getAdminClass()::query()->truncate();

        $admin = app(ModelRegister::class)->getAdminClass()->query()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'phone' => '18888888888',
            'password' => bcrypt('abc123'),
        ]);

        /**
         * @var Role $role
         */
        $role = app(ModelRegister::class)->getRoleClass()->query()->updateOrCreate([
            'name' => '超级管理员',
            'guard_name' => 'sanctum',
            'is_super' => true,
        ]);

        $permissions = app(ModelRegister::class)->getPermissionClass()->query()->get(['id']);
        $role->givePermissionTo(array_column($permissions->toArray(), 'id'));

        $admin->assignRole($role);
    }
}
