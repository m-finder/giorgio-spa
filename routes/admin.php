<?php

use Giorgio\Http\Controllers\Auth\LoginController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::prefix(config('admin.prefix'))->middleware(['web'])->group(function () {

    Route::middleware(['admin-auth:sanctum', 'verified'])->group(function (){
        Route::get('/', function () {dd('/');});
    });

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.showLogin');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');

});
