<?php

namespace GT264\LoginFiesta;

use GT264\LoginFiesta\Commands\LoginFiestaCommand;
use Illuminate\Support\ServiceProvider;

class LoginFiestaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/login-fiesta.php' => config_path('login-fiesta.php'),
        ], 'login-fiesta-config');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                LoginFiestaCommand::class,
            ]);
        }
    }

    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/login-fiesta.php',
            'login-fiesta'
        );

        $this->app->singleton(LoginFiesta::class, function ($app) {
            return new LoginFiesta($app['config']->get('login-fiesta', []));
        });
    }
}