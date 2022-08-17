<?php

namespace GiorgioSpa\Database\Seeders;

use GiorgioSpa\Models\Admin;
use GiorgioSpa\Models\Role;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //清空表 然后再填充数据
        Admin::query()->truncate();
        /**
         * @var Admin $admin
         * @var Role $role
         */
        $admin = Admin::query()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'phone' => '18888888888',
            'password' => bcrypt('abc123'),
        ]);

        Role::query()->truncate();
        $role = Role::query()->create([
            'name' => '超级管理员',
            'guard_name' => 'custom',
            'is_super' => true
        ]);

        $admin->assignRole($role);
    }
}
