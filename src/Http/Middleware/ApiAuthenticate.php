<?php

namespace GiorgioSpa\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ApiAuthenticate
{

    public function handle($request, Closure $next)
    {
        $url = str_replace($request->route()->getPrefix(), '',$request->route()->uri);
        $url_replace = preg_replace("/\{.*\}/", '{*}', $url);
        if (Auth::user()->hasAuth($url_replace)) {
            return $next($request);
        }
        throw new \Exception('您没有访问该对象权限。');
    }
}
