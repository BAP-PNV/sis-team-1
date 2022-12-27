<?php

namespace App\Services;

use Illuminate\Support\ServiceProvider;
use Services\Implements\AuthService;

class ImplementsServiceProvider implements ServiceProvider
{
    public function register()
    {

        $this->app->bind(
            IAuthService::class,
            AuthService::class
        );
    }
    public function boot()
    {
    }
}
