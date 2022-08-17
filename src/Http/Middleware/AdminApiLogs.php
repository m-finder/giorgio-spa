<?php

namespace GiorgioSpa\Http\Middleware;

use GiorgioSpa\Models\AdminApiLog;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminApiLogs
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_info = Auth::user();
        if (!is_null($user_info)) {
            $user = $user_info->email;
            $request_url = $request->fullUrl();
            $request_content = json_encode($request->all());
            AdminApiLog::query()->create(compact("request_url", "request_content", "user"));
        }
        return $next($request);
    }
}
