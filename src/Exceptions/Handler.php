<?php

namespace App\Exceptions;

use ErrorException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * 接口返回捕获异常信息
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        $code = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : $e->getCode();
        $message = $e->getMessage();
        if ($e instanceof AuthenticationException && $code == 0) {
            $code = 401;
            $message = '登录已失效';
        }elseif($e instanceof ModelNotFoundException && $code == 0){
            $code = 404;
        }
        if ($e instanceof ValidationException) {
            $code = 400;
            $message = $e->validator->errors()->first();
        }
        if ($request->expectsJson()) {
            $res = [
                'code' => $code ?: 500,
                'msg' => $message,
                'data' => []
            ];
            info('接口响应',$res);
            if(!config('app.debug') && ($e instanceof QueryException || $e instanceof ErrorException)){
                $res['msg'] = 'Server Error';
            }
            return response()->json($res)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
        return parent::render($request, $e);

    }
}
