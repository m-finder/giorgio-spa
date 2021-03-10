<?php

namespace Giorgio\Providers;

use Giorgio\Console\Commands\InstallCommand;
use Giorgio\Console\Commands\MigrationCommand;
use Giorgio\Http\Controllers\Auth\LoginController;
use Giorgio\Http\Middleware\AdminAuthenticate;
use Giorgio\Http\Middleware\AdminRedirectIfAuthenticated;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;

class GiorgioServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->registerConfig();
        $this->registerRoutes();
        $this->registerCommand();
        $this->registerMiddleware();

        if ($this->app->runningInConsole()) {
            $this->publishing();
        }
    }

    public function register()
    {
        $this->app->when([LoginController::class, AttemptToAuthenticate::class, RedirectIfTwoFactorAuthenticatable::class])
            ->needs(StatefulGuard::class)
            ->give(function () {
                return Auth::guard('admin');
            });

        Inertia::share('adminName', config('admin.name'));
    }

    protected function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/admin.php', 'admin');

        config(Arr::dot(config('admin.auth', []), 'auth.'));
    }

    protected function registerRoutes()
    {
        foreach (config('admin.routes') as $group) {
            Route::group(['namespace' => $group['namespace']], function () use ($group) {
                $this->loadRoutesFrom(__DIR__ . '/../../routes/' . $group['name'] . '.php');
            });
        }
    }

    protected function registerCommand()
    {
        $this->commands([
            InstallCommand::class,
            MigrationCommand::class,
        ]);
    }

    protected function registerMiddleware()
    {
        $this->app['router']->aliasMiddleware('auth.admin', AdminAuthenticate::class);
        $this->app['router']->aliasMiddleware('admin', AdminRedirectIfAuthenticated::class);
    }

    protected function publishing()
    {
        $this->publishes([__DIR__ . '/../../config/admin.php' => config_path('admin.php')], 'admin');
        $this->publishes([__DIR__ . '/../../database/migrations' => database_path('migrations/admin')]);
        $this->publishes([__DIR__ . '/../../resources' => base_path('resources')], 'resources');
    }
}
