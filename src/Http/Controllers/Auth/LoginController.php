<?php

namespace Giorgio\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Contracts\LogoutResponse;

class LoginController extends Controller
{
    protected $name = 'admin';
    protected $guard;

    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    public function showLoginForm(): Response
    {
        return Inertia::render('Admin/Auth/Login');
    }

    public function login()
    {
        dd($this->guard);
    }

    public function logout(Request $request): LogoutResponse
    {
        $this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return app(LogoutResponse::class);
    }
}
