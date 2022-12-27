<?php

namespace App\Services;

use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\IAwsService;
use Services\Implements\AwsS3Service;
use Services\Implements\AuthService;

class ImplementsServiceProvider implements ServiceProvider
{
    public function register()
    {

        $this->app->bind(
            IAuthService::class,
            AuthService::class
        );
        $this->app->bind(
            IAwsService::class,
            AwsS3Service::class
        );
    }
    public function boot()
    {
    }
}
