<?php

namespace GiorgioSpa\Providers;

use GiorgioSpa\Console\InitDatabaseCommand;
use GiorgioSpa\Console\InstallCommand;
use GiorgioSpa\Console\UpdatePackageCommand;
use GiorgioSpa\Http\Middleware\AdminApiLogs;
use GiorgioSpa\Http\Middleware\ApiAuthenticate;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class GiorgioSpaServiceProvider extends ServiceProvider
{

    protected $routeMiddleware = [
        'admin.api.log' => AdminApiLogs::class,
        'admin.api.permission' => ApiAuthenticate::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/admin.php', 'admin'
        );

        config(Arr::dot(config('admin.auth', []), 'auth.'));

        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        $this->commands([
            InstallCommand::class,
            InitDatabaseCommand::class,
            UpdatePackageCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../../resources' => base_path('resources')
            ]);

            $this->publishes([
                __DIR__ . '/../../views' => base_path('resources/views')
            ]);

            $this->publishes([
                __DIR__ . '/../../database/migrations' => database_path('migrations')
            ]);

        }

        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
    }
}
