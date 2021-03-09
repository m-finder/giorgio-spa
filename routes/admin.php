<?php

use Giorgio\Http\Controllers\Auth\LoginController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::prefix(config('admin.prefix'))->middleware(['web'])->group(function () {

    Route::middleware(['admin:admin'])->group(function (){
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.showLogin');
        Route::post('login', [LoginController::class, 'login'])->name('admin.login');
        Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');

        Route::get('/', function () {dd('/');})->name('admin.index');
        Route::get('/dashboard', function () {dd('dashboard');})->name('admin.dashboard');
    });

    Route::middleware(['auth.admin:admin'])->group(function (){
        Route::get('/', function () {dd('/');})->name('admin.index');
        Route::get('/dashboard', function () {dd('dashboard');})->name('admin.dashboard');
    });
});
