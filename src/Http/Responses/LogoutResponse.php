<?php


namespace Giorgio\Http\Responses;


use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LogoutResponse as BaseLogoutResponse;

class LogoutResponse implements BaseLogoutResponse
{

    public function toResponse($request)
    {
        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect(route('admin.logout'));
    }
}
