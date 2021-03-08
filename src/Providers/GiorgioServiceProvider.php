<?php

namespace Giorgio\Providers;

use Giorgio\Console\Commands\InstallCommand;
use Giorgio\Console\Commands\MigrationCommand;
use Giorgio\Http\Middleware\AdminAuthenticate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
        $this->app['router']->aliasMiddleware('admin-auth', AdminAuthenticate::class);
    }

    protected function publishing()
    {
        $this->publishes([__DIR__ . '/../../config/admin.php' => config_path('admin.php')], 'admin');
        $this->publishes([__DIR__ . '/../../database/migrations' => database_path('migrations/admin')]);
    }
}
