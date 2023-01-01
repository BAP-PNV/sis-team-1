<?php

namespace App\Services;

use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\IAwsService;
use App\Services\Implements\AwsS3Service;
use App\Services\Implements\AuthService;

class ImplementsServiceProvider extends ServiceProvider
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
