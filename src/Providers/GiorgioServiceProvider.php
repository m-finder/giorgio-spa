<?php

namespace GiorgioSpa\Providers;

use GiorgioSpa\Console\InitDatabaseCommand;
use GiorgioSpa\Console\InstallCommand;
use GiorgioSpa\Http\Middleware\GiorgioSpaPermission;
use GiorgioSpa\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class GiorgioServiceProvider extends ServiceProvider
{
    /**
     * auth middlewares.
     *
     * @var string[]
     */
    protected array $routeMiddleware = [
        'giorgio.permission' => GiorgioSpaPermission::class,
    ];

    public function boot(): void
    {
        $this->registerConfig();
        $this->registerCommand();
        $this->registerMiddleware();
        $this->registerTokenCheck();

        if ($this->app->runningInConsole()) {
            $this->publishing();
        }

        if (file_exists(base_path('routes/giorgio.php'))) {
            $domain = config('giorgio.domain');
            Route::middleware('backend')
                ->domain($domain)
                ->group(base_path('routes/giorgio.php'));
            $this->loadRoutesFrom(base_path('routes/giorgio.php'));
        }
    }

    public function register()
    {
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/giorgio.php', 'giorgio');
    }

    protected function registerMiddleware(): void
    {
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }
    }

    protected function registerCommand(): void
    {
        $this->commands([
            InstallCommand::class,
            InitDatabaseCommand::class,
        ]);
    }

    protected function publishing(): void
    {
        $this->publishes([__DIR__.'/../../config' => config_path()]);
        $this->publishes([__DIR__.'/../../routes/giorgio.php' => base_path('routes/giorgio.php')]);
        $this->publishes([__DIR__.'/../../database/migrations' => database_path('migrations/admin')]);
        $this->publishes([__DIR__.'/../../resources' => base_path('resources')], 'resources');
        $this->publishes([__DIR__.'/../../src/Exceptions/Handler.php' => base_path('app/Exceptions/Handler.php')]);
        $this->publishes([__DIR__.'/../../spa-stubs' => base_path('')]);
        if (file_exists('./vite.config.js')) {
            del_file('./vite.config.js');
        }
    }

    protected function registerTokenCheck(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        //自定义token是否过期的方法
        Sanctum::authenticateAccessTokensUsing(function ($accessToken, $isValid) {
            $expiration = config('sanctum.expiration', 120);
            $time = $accessToken->last_used_at ?? $accessToken->created_at;

            return $time->gt(now()->subMinutes($expiration));
        });
    }
}
