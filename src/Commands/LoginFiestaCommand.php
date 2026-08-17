<?php

namespace GT264\LoginFiesta\Commands;

use Illuminate\Console\Command;
use GT264\LoginFiesta\LoginFiesta;

class LoginFiestaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'login-fiesta:greet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Output a greeting from the Login Fiesta package';

    /**
     * Execute the console command.
     */
    public function handle(LoginFiesta $loginFiesta): int
    {
        $this->info($loginFiesta->fiesta());

        return self::SUCCESS;
    }
}