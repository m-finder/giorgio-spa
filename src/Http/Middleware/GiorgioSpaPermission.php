<?php

namespace GiorgioSpa\Http\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class GiorgioSpaPermission
{

    public function handle(Request $request, Closure $next)
    {
        $route = Route::currentRouteName();
        $domain = $request->header('host');
        $token = $request->user()->currentAccessToken();

        if ($token->getAttribute('name') == 'invalid_token' && config('app.env') != 'local') {
            $token->delete();
            abort(401, '当前账户已在其他地方登陆,被迫下线');
        }

        //自动排除.list路由,不判断权限
        if (Str::endsWith($route, '.list')) {
            return $next($request);
        }

        //自动排除白名单路由,不判断权限
        if (in_array($route, config('permission.white_list'))) {
            return $next($request);
        }

        $permission = Permission::query()->where('name', '=', $route)->first();
        if (empty($permission)) {
            abort(404, '权限不存在');
        }

        if (!admin()->isSuper() && !admin()->hasPermissionTo($permission)) {
            abort(403, '无此权限');
        }
        return $next($request);
    }

}
