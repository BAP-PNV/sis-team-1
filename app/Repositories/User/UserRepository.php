<?php
namespace App\Repositories\User;

use App\Repositories\Eloquent\BaseRepository;


class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\User::class;
    }
    public function getUser()
    {
        return $this->model->select('name')->take(2)->get();
    }
}