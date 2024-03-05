<?php

namespace GiorgioSpa\Database\Seeders;

use GiorgioSpa\Services\ModelRegister;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = Route::getRoutes();
        foreach ($routes as $route) {
            $uri = $route->uri;
            $method = $route->methods[0];
            $action = $route->action;
            $guard = 'admin';
            $name = $action['as'] ?? '';
            if (! isset($action['domain'])) {
                continue;
            }

            if ($uri == '/' || in_array($name, config('permission.white_list'))) {
                continue;
            }

            if (empty($name)) {
                continue;
            }
            $nameArray = explode('.', $name);

            if ($nameArray[1] == 'list') {
                continue;
            }

            abort_if(Str::plural($nameArray[0]) !== $nameArray[0], 400, 'group:'.$nameArray[0].'为非复数');

            $groups = config('permission.groups');
            abort_if(! isset($groups[$nameArray[0]]), 400, $nameArray[0].'未在group中配置');

            $methods = config('permission.methods');
            abort_if(! isset($methods[$nameArray[1]]), 400, $nameArray[1].'未在method中配置');

            app(ModelRegister::class)->getPermissionClass()::query()->updateOrCreate([
                'name' => $name,
                'type' => $guard,
            ], [
                'name' => $name,
                'name_zh_cn' => $groups[$nameArray[0]].$methods[$nameArray[1]],
                'method' => $method,
                'uri' => $uri,
                'guard_name' => 'sanctum',
                'type' => $guard,
            ]);
        }
    }
}
