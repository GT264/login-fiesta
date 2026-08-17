<?php

use GT264\LoginFiesta\Facades\LoginFiesta as LoginFiestaFacade;
use GT264\LoginFiesta\LoginFiesta;

it('resolves the LoginFiesta instance from the container', function () {
    $instance = app(LoginFiesta::class);

    expect($instance)->toBeInstanceOf(LoginFiesta::class);
});

it('returns a greeting via the main class', function () {
    $loginFiesta = app(LoginFiesta::class);

    expect($loginFiesta->fiesta())->toBe('Login Fiesta!');
});

it('exposes the facade', function () {
    expect(LoginFiestaFacade::fiesta())->toBe('Login Fiesta!');
});

it('merges the package configuration', function () {
    expect(config('login-fiesta'))->toBeArray();
});