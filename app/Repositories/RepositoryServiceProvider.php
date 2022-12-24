<?php

namespace App\Repositories;

use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Interfaces\IAuthService;
use App\Services\Implements\AuthService;


use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            RepositoryInterface::class,
            BaseRepository::class
        );
    
        $this->app->singleton(
            \App\Repositories\User\UserRepositoryInterface::class,
            \App\Repositories\User\UserRepository::class
        );
    }
    
    public function boot()
    {

    }
}