<?php
namespace Giorgio\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as BaseLoginResponse;

class LoginResponse implements BaseLoginResponse
{

    public function toResponse($request)
    {
        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended('admin/dashboard');
    }
}
