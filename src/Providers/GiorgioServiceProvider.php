<?php

namespace GiorgioSpa\Providers;

use App\Models\PersonalAccessToken;
use GiorgioSpa\Console\InitDatabaseCommand;
use GiorgioSpa\Console\InstallCommand;
use GiorgioSpa\Http\Middleware\GiorgioSpaPermission;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class GiorgioServiceProvider extends ServiceProvider
{

    /**
     * auth middlewares.
     * @var string[]
     */
    protected array $routeMiddleware = [
        'giorgio.permission' => GiorgioSpaPermission::class,
    ];

    protected array $middlewareGroups = [
        'admin' => [
            'throttle:api',
            SubstituteBindings::class,
        ]
    ];

    public function boot()
    {
        $this->registerConfig();
        $this->registerCommand();
        $this->registerMiddleware();
        $this->registerTokenCheck();

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

        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
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
        $this->publishes([__DIR__ . '/../../src/Exceptions/Handler.php' => base_path('app/Exceptions/Handler.php')]);
        $this->publishes([__DIR__ . '/../../spa-stubs' => base_path('')]);
        if(file_exists('./vite.config.js')){
            del_file('./vite.config.js');
        }
    }

    protected function registerTokenCheck()
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        //自定义token是否过期的方法
        Sanctum::authenticateAccessTokensUsing(function ($accessToken, $isValid){
            $expiration = config('sanctum.expiration');
            $time = $accessToken->last_used_at??$accessToken->created_at;
            return $time->gt(now()->subMinutes($expiration));
        });
    }
}