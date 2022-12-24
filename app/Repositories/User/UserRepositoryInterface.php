<?php
namespace App\Repositories\User;

use App\Repositories\Interfaces\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function getUser();
}