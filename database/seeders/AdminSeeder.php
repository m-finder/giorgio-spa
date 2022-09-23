<?php

namespace GiorgioSpa\Database\Seeders;

use GiorgioSpa\Services\ModelRegister;
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
        app(ModelRegister::class)->getAdminClass()::query()->truncate();
   
        $admin = app(ModelRegister::class)->getAdminClass()::query()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'phone' => '18888888888',
            'password' => bcrypt('abc123'),
        ]);

        $role = app(ModelRegister::class)->getRoleClass()::query()->updateOrCreate([
            'name' => '超级管理员',
            'guard_name' => 'custom',
            'is_super' => true
        ]);

        $admin->assignRole($role);
    }
}
