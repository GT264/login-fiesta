<?php

namespace GT264\LoginFiesta\Tests;

use GT264\LoginFiesta\LoginFiestaServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * Get the package service providers.
     */
    protected function getPackageProviders($app): array
    {
        return [
            LoginFiestaServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('login-fiesta', require __DIR__.'/../config/login-fiesta.php');
    }
}