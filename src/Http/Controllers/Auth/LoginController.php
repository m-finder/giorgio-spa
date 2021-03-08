<?php
namespace Giorgio\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function showLoginForm(): Response
    {
        return Inertia::render('Admin/Auth/Login');
    }

    public function login()
    {
        dd(11);
    }
}
