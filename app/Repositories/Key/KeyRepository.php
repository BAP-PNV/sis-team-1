<?php
namespace App\Repositories\Key;

use App\Repositories\Eloquent\BaseRepository;

class KeyRepository extends BaseRepository implements IKeyRepository{
    public function getModel()
    {
        return \App\Repositories\Key\KeyRepository::class;
    }
    public function hasKey(string $string)
    {
        $this->model->where('access_key','=',$string);
    }
}
