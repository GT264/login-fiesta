<?php

namespace GT264\LoginFiesta;

class LoginFiesta
{
    public function __construct(
        protected array $config = []
    ) {
    }

    /**
     * Return a friendly greeting as a placeholder for package functionality.
     */
    public function fiesta(): string
    {
        return 'Login Fiesta!';
    }
}