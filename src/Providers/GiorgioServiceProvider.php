<?php

namespace GiorgioSpa\Providers;

use GiorgioSpa\Console\InitDatabaseCommand;
use GiorgioSpa\Console\InstallCommand;
use GiorgioSpa\Http\Middleware\GiorgioSpaPermission;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class GiorgioServiceProvider extends ServiceProvider
{

    /**
     * auth middlewares.
     * @var string[]
     */
    protected array $routeMiddleware = [
        'giorgio.permission' => GiorgioSpaPermission::class,
    ];

    public function boot()
    {
        $this->registerConfig();
        $this->registerCommand();
        $this->registerMiddleware();

        if ($this->app->runningInConsole()) {
            $this->publishing();
        }

        if(file_exists(base_path('routes/giorgio.php'))){
            $this->loadRoutesFrom(base_path('routes/giorgio.php'));
        }
    }

    public function register()
    {

    }

    protected function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/giorgio.php', 'giorgio');

        config(Arr::dot(config('giorgio.auth', []), 'auth.'));
    }

    protected function registerMiddleware()
    {
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }
    }

    protected function registerCommand()
    {
        $this->commands([
            InstallCommand::class,
            InitDatabaseCommand::class,
        ]);
    }

    protected function publishing()
    {
        $this->publishes([__DIR__ . '/../../config' => config_path()]);
        $this->publishes([__DIR__ . '/../../routes/giorgio.php' => base_path('routes/giorgio.php')]);
        $this->publishes([__DIR__ . '/../../database/migrations' => database_path('migrations/admin')]);
        $this->publishes([__DIR__ . '/../../resources' => base_path('resources')], 'resources');
        $this->publishes([__DIR__ . '/../../spa-stubs' => base_path('')]);
        if(file_exists('./vite.config.js')){
            del_file('./vite.config.js');
        }
    }
}