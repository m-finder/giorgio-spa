<?php

namespace GiorgioSpa\Database\Seeders;

use GiorgioSpa\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $routes = Route::getRoutes();
        foreach ($routes as $route) {
            $uri = $route->uri;
            $method = $route->methods[0];
            $action = $route->action;
            $guard = 'admin';
            $name = $action['as'] ?? '';
            if (!Str::startsWith($uri,'admin') || in_array($name, config('permission.white_list'))) {
                continue;
            }
            if (empty($name)) {
                continue;
            }
            $nameArray = explode('.', $name);

            if ($nameArray[1] == 'list') {
                continue;
            }
            if (Str::plural($nameArray[0]) !== $nameArray[0]) {
                abort(400,'group:' . $nameArray[0] . '为非复数');
            }
            $groups = config('permission.groups');
            if (!isset($groups[$nameArray[0]])) {
                abort(400,$nameArray[0] . '未在group中配置');
            }
            $methods = config('permission.methods');
            if (!isset($methods[$nameArray[1]])) {
                abort(400,$nameArray[1] . '未在method中配置');
            }
            Permission::query()->truncate();
            Permission::query()->updateOrCreate([
                'name' => $name,
                'type' => $guard
            ], [
                'name' => $name,
                'name_zh_cn' => $groups[$nameArray[0]] . $methods[$nameArray[1]],
                'method' => $method,
                'uri' => $uri,
                'guard_name' => 'custom',
                'type' => $guard
            ]);
        }

    }

}
