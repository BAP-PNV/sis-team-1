<?php

namespace App\Repositories;

use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Folder\IFolderRepository;
use App\Repositories\Folder\FolderRepository;
use App\Repositories\Image\IImageRepository;
use App\Repositories\Image\ImageRepository;
use App\Repositories\Interfaces\IRepository;
use App\Repositories\Key\IKeyRepository;
use App\Repositories\Key\KeyRepository;
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
            IRepository::class,
            BaseRepository::class
        );

        $this->app->singleton(
            \App\Repositories\User\IUserRepository::class,
            \App\Repositories\User\UserRepository::class
        );
        $this->app->singleton(
            IKeyRepository::class,
            KeyRepository::class
        );
        $this->app->singleton(
            IImageRepository::class,
            ImageRepository::class
        );
        $this->app->singleton(
            IFolderRepository::class,
            FolderRepository::class
        );
    }

    public function boot()
    {
    }
}
