<?php
namespace Giorgio\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate extends Middleware
{
//    public function handle($request, Closure $next, $guards = 'admin')
//    {
//        if (!Auth::guard($guards)->check()) {
//            if ($request->expectsJson()) {
//                return response('Unauthorized.', 401);
//            }
//            return redirect(route('admin.showLogin'));
//        }
//        return $next($request);
//    }


    /**
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('admin.showLogin');
        }
    }
}
