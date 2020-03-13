<?php


namespace GiorgioSpa\Seeder;

use GiorgioSpa\Models\Admin;
use GiorgioSpa\Models\Element;
use GiorgioSpa\Models\Menu;
use GiorgioSpa\Models\Role;
use GiorgioSpa\Models\RoleElement;
use GiorgioSpa\Models\RoleMenu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Role::truncate();
        Role::create([
            'name' => 'super-admin',
            'alias' => '超级管理员'
        ]);

        Admin::truncate();
        Admin::create([
            'name' => 'admin',
            'password' => Hash::make('111111'),
            'role_id' => 1,
            'email' => 'admin@admin.com',
            'avatar' => '/images/avatar.jpg',
            'api_token' => (string)Str::uuid(),
        ]);

        $menus = [
            [
                'id' => 1,
                'parent_id' => 0,
                'name' => 'Home',
                'title' => '初始菜单',
                'path' => '/',
                'icon' => 'dashboard',
                'redirect' => '/dashboard',
                'component' => 'Layout',
                'affix' => false,
            ],
            [
                'id' => 2,
                'parent_id' => 1,
                'name' => 'Dashboard',
                'title' => '首页',
                'path' => '/dashboard',
                'icon' => 'dashboard',
                'redirect' => '',
                'component' => '/dashboard/Index',
                'affix' => true,
            ],
            [
                'id' => 3,
                'parent_id' => 0,
                'name' => 'Auth',
                'title' => '权限管理',
                'path' => '/auth',
                'icon' => 'auth',
                'redirect' => '/auth/roles',
                'component' => 'Layout',
                'affix' => false,
            ],
            [
                'id' => 4,
                'parent_id' => 3,
                'name' => 'RoleList',
                'title' => '角色列表',
                'path' => '/auth/roles',
                'icon' => 'role',
                'redirect' => '',
                'component' => '/roles/Index',
                'affix' => false,
            ],
            [
                'id' => 5,
                'parent_id' => 3,
                'name' => 'AdminList',
                'title' => '用户列表',
                'path' => '/auth/admins',
                'icon' => 'user',
                'redirect' => '',
                'component' => '/admins/Index',
                'affix' => false,
            ],
            [
                'id' => 6,
                'parent_id' => 3,
                'name' => 'MenuList',
                'title' => '权限列表',
                'path' => '/auth/menus',
                'icon' => 'security',
                'redirect' => '',
                'component' => '/menus/Index',
                'affix' => false,
            ],
            [

                'id' => 7,
                'parent_id' => 0,
                'name' => 'Base',
                'title' => '其他',
                'path' => '/base',
                'icon' => 'smile',
                'component' => 'Layout',
                'redirect' => '/base/icons',
                'affix' => false,
            ],
            [
                'id' => 8,
                'parent_id' => 7,
                'name' => 'Icons',
                'title' => '图标',
                'path' => '/base/icons',
                'icon' => 'discount',
                'redirect' => '',
                'component' => '/icons/Index',
                'affix' => false,
            ],
            [

                'id' => 9,
                'parent_id' => 7,
                'name' => 'log',
                'title' => '操作日志',
                'path' => '/base/logs',
                'icon' => 'history',
                'redirect' => '',
                'component' => '/logs/Index',
                'affix' => false,
            ]
        ];
        Menu::truncate();
        Menu::insert($menus);

        $elements = [
            [
                'name' => '角色列表',
                'menu_id' => 4,
                'code' => 'role:list',
                'method' => 'get',
                'path' => '/roles/list',
            ], [
                'name' => '所有角色',
                'menu_id' => 4,
                'code' => 'role:all',
                'method' => 'get',
                'path' => '/roles/all',
            ], [
                'name' => '添加角色',
                'menu_id' => 4,
                'code' => 'role:add',
                'method' => 'post',
                'path' => '/roles/create',
            ], [
                'name' => '修改角色',
                'menu_id' => 4,
                'code' => 'role:edit',
                'method' => 'put',
                'path' => '/roles/{*}/update',
            ], [
                'name' => '删除角色',
                'menu_id' => 4,
                'code' => 'role:delete',
                'method' => 'delete',
                'path' => '/roles/{*}/delete',
            ], [
                'name' => '角色详情',
                'menu_id' => 4,
                'code' => 'role:detail',
                'method' => 'get',
                'path' => '/roles/{*}/detail',
            ], [
                'name' => '角色权限',
                'menu_id' => 4,
                'code' => 'role:auth',
                'method' => 'get',
                'path' => '/roles/{*}/auth',
            ], [
                'name' => '分配权限',
                'menu_id' => 4,
                'code' => 'role:setAuth',
                'method' => 'get',
                'path' => '/roles/{*}/set/auth',
            ], [
                'name' => '所有权限',
                'menu_id' => 4,
                'code' => 'role:menus',
                'method' => 'get',
                'path' => '/menus/all/with/elements',
            ], [
                'name' => '用户列表',
                'menu_id' => 5,
                'code' => 'admin:list',
                'method' => 'get',
                'path' => '/admins/list',
            ], [
                'name' => '添加用户',
                'menu_id' => 5,
                'code' => 'admin:add',
                'method' => 'post',
                'path' => '/admins/create',
            ], [
                'name' => '修改用户',
                'menu_id' => 5,
                'code' => 'admin:edit',
                'method' => 'put',
                'path' => '/admins/{*}/update',
            ], [
                'name' => '删除用户',
                'menu_id' => 5,
                'code' => 'admin:delete',
                'method' => 'delete',
                'path' => '/admins/{*}/delete',
            ], [
                'name' => '用户详情',
                'menu_id' => 5,
                'code' => 'admin:detail',
                'method' => 'get',
                'path' => '/admins/{*}/detail',
            ], [
                'name' => '所有菜单',
                'menu_id' => 6,
                'code' => 'menu:all',
                'method' => 'get',
                'path' => '/menus/all',
            ], [
                'name' => '添加菜单',
                'menu_id' => 6,
                'code' => 'menu:add',
                'method' => 'post',
                'path' => '/menus/create',
            ], [
                'name' => '修改菜单',
                'menu_id' => 6,
                'code' => 'menu:edit',
                'method' => 'put',
                'path' => '/menus/{*}/update',
            ], [
                'name' => '删除菜单',
                'menu_id' => 6,
                'code' => 'menu:delete',
                'method' => 'delete',
                'path' => '/menus/{*}/delete',
            ], [
                'name' => '菜单详情',
                'menu_id' => 6,
                'code' => 'menu:detail',
                'method' => 'get',
                'path' => '/menus/{*}/detail',
            ], [
                'name' => '上级菜单',
                'menu_id' => 6,
                'code' => 'menu:parents',
                'method' => 'get',
                'path' => '/menus/parents',
            ], [
                'name' => '资源列表',
                'menu_id' => 6,
                'code' => 'elements:list',
                'method' => 'get',
                'path' => '/elements/list',
            ], [
                'name' => '添加资源',
                'menu_id' => 6,
                'code' => 'element:add',
                'method' => 'post',
                'path' => '/elements/create',
            ], [
                'name' => '修改资源',
                'menu_id' => 6,
                'code' => 'element:edit',
                'method' => 'put',
                'path' => '/elements/{*}/update',
            ], [
                'name' => '删除资源',
                'menu_id' => 6,
                'code' => 'element:delete',
                'method' => 'delete',
                'path' => '/element/{*}/delete',
            ], [
                'name' => '资源详情',
                'menu_id' => 6,
                'code' => 'element:detail',
                'method' => 'get',
                'path' => '/elements/{*}/detail',
            ], [
                'name' => '日志列表',
                'menu_id' => 9,
                'code' => 'logs:list',
                'method' => 'get',
                'path' => '/logs/list',
            ]
        ];
        Element::truncate();
        Element::insert($elements);

        RoleMenu::truncate();
        $role_menus = [
            ['permission_id' => 1, 'role_id' => 1],
            ['permission_id' => 2, 'role_id' => 1],
            ['permission_id' => 3, 'role_id' => 1],
            ['permission_id' => 4, 'role_id' => 1],
            ['permission_id' => 5, 'role_id' => 1],
            ['permission_id' => 6, 'role_id' => 1],
            ['permission_id' => 7, 'role_id' => 1],
            ['permission_id' => 8, 'role_id' => 1],
            ['permission_id' => 9, 'role_id' => 1],
        ];

        RoleMenu::insert($role_menus);

        RoleElement::truncate();
        $role_elements = [
            ['element_id' => 1, 'role_id' => 1],
            ['element_id' => 2, 'role_id' => 1],
            ['element_id' => 3, 'role_id' => 1],
            ['element_id' => 4, 'role_id' => 1],
            ['element_id' => 5, 'role_id' => 1],
            ['element_id' => 6, 'role_id' => 1],
            ['element_id' => 7, 'role_id' => 1],
            ['element_id' => 8, 'role_id' => 1],
            ['element_id' => 9, 'role_id' => 1],
            ['element_id' => 10, 'role_id' => 1],
            ['element_id' => 11, 'role_id' => 1],
            ['element_id' => 12, 'role_id' => 1],
            ['element_id' => 13, 'role_id' => 1],
            ['element_id' => 14, 'role_id' => 1],
            ['element_id' => 15, 'role_id' => 1],
            ['element_id' => 16, 'role_id' => 1],
            ['element_id' => 17, 'role_id' => 1],
            ['element_id' => 18, 'role_id' => 1],
            ['element_id' => 19, 'role_id' => 1],
            ['element_id' => 20, 'role_id' => 1],
            ['element_id' => 21, 'role_id' => 1],
            ['element_id' => 22, 'role_id' => 1],
            ['element_id' => 23, 'role_id' => 1],
            ['element_id' => 24, 'role_id' => 1],
            ['element_id' => 25, 'role_id' => 1],
            ['element_id' => 26, 'role_id' => 1],
        ];

        RoleElement::insert($role_elements);
    }
}