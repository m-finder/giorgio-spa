<?php

Route::get('/email', function () {
    return new GiorgioSpa\Mail\AdminResetPassword(rand(100000, 999999));
});

Route::get(config('admin.uri'), function () {
    return view('admin');
});

Route::prefix('admin-api')->namespace('\GiorgioSpa\Http\Controllers')
    ->group(function () {

        Route::post('/login', 'LoginController@login');
        Route::post('/send/mail/reset/password', 'MailController@resetPassword');
        Route::post('/reset/password/by/mail', 'AdminController@resetPasswordByMail');

        Route::middleware(['auth:admin-api', 'admin.api.log'])->group(function () {
            Route::get('/admins/auth', 'AdminController@adminAuth');
            Route::put('/admins/reset/password', 'AdminController@resetPassword');
            Route::post('/admins/avatar/upload', 'AdminController@avatarUpload');
            Route::get('/admins/detail/by/auth', 'AdminController@detailByAuth');
            Route::put('/admins/reset/info', 'AdminController@resetInfo');
        });

        Route::middleware(['auth:admin-api', 'admin.api.permission', 'admin.api.log'])
            ->group(function () {
                Route::get('/admins/list', 'AdminController@lists');
                Route::post('/admins/create', 'AdminController@create');
                Route::get('/admins/{id}/detail', 'AdminController@detail');
                Route::put('/admins/{id}/update', 'AdminController@update');
                Route::delete('/admins/{id}/delete', 'AdminController@delete');

                Route::get('/menus/list', 'MenuController@lists');
                Route::get('/menus/all', 'MenuController@all');
                Route::get('/menus/all/with/elements', 'MenuController@allWithElements');
                Route::get('/menus/parents', 'MenuController@parents');
                Route::post('/menus/create', 'MenuController@create');
                Route::get('/menus/{id}/detail', 'MenuController@detail');
                Route::put('/menus/{id}/update', 'MenuController@update');
                Route::delete('/menus/{id}/delete', 'MenuController@delete');

                Route::get('/elements/list', 'ElementController@lists');
                Route::post('/elements/create', 'ElementController@create');
                Route::put('/elements/{id}/update', 'ElementController@update');
                Route::get('/elements/{id}/detail', 'ElementController@detail');
                Route::delete('/elements/{id}/delete', 'ElementController@delete');

                Route::get('roles/list', 'RoleController@lists');
                Route::get('/roles/all', 'RoleController@all');
                Route::post('/roles/create', 'RoleController@create');
                Route::put('/roles/{id}/update', 'RoleController@update');
                Route::get('/roles/{id}/detail', 'RoleController@detail');
                Route::delete('/roles/{id}/delete', 'RoleController@delete');
                Route::get('/roles/{id}/auth', 'RoleController@auth');
                Route::post('/roles/{id}/set/auth', 'RoleController@setAuth');


                Route::get('/logs/list', 'AdminApiLogController@lists');
            });
    });
