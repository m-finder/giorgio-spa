<?php

/** @noinspection DuplicatedCode */

use GiorgioSpa\Http\Controllers\Admin\System\AdminController;
use GiorgioSpa\Http\Controllers\Admin\System\AuthController;
use GiorgioSpa\Http\Controllers\Admin\System\FileController;
use GiorgioSpa\Http\Controllers\Admin\System\PasswordController;
use GiorgioSpa\Http\Controllers\Admin\System\PermissionController;
use GiorgioSpa\Http\Controllers\Admin\System\RoleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix(config('giorgio.prefix'))->group(function () {
    Route::get('/', function () {
        return view('admin');
    });

    Route::post('captcha', function () {
        return response()->json([
            'data' => app('captcha')->create('default', true),
            'msg' => '操作成功',
            'status' => 200,
        ]);
    })->name('captcha');

    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::group([
        'middleware' => ['auth:sanctum', 'giorgio.permission'],
    ], function () {
        Route::patch('password/{id}', [PasswordController::class, 'reset'])->name('passwords.reset');
        Route::put('password/{id}', [PasswordController::class, 'update'])->name('passwords.update');

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::post('file', [FileController::class, 'upload'])->name('files.upload');

        Route::get('permissions/list', [PermissionController::class, 'list'])->name('permissions.list');
        Route::apiresource('permissions', PermissionController::class, ['only' => 'index']);

        Route::get('admins/list', [AdminController::class, 'list'])->name('admins.list');
        Route::apiresource('admins', AdminController::class, ['except' => ['show']]);

        Route::patch('roles/{id}/auth', [RoleController::class, 'auth'])->name('roles.auth');
        Route::get('roles/list', [RoleController::class, 'list'])->name('roles.list');
        Route::apiresource('roles', RoleController::class, ['except' => ['show']]);
    });
});
