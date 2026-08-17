<?php

namespace GT264\LoginFiesta\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string fiesta()
 *
 * @see \GT264\LoginFiesta\LoginFiesta
 */
class LoginFiesta extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \GT264\LoginFiesta\LoginFiesta::class;
    }
}