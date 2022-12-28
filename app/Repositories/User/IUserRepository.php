<?php
namespace App\Repositories\User;

use App\Repositories\Interfaces\IRepository;

interface IUserRepository extends IRepository
{
    public function getUser();
}