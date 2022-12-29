<?php

namespace App\Repositories\Key;

use App\Repositories\Eloquent\BaseRepository;

class KeyRepository extends BaseRepository implements IKeyRepository
{
    public function getModel()
    {
        return \App\Models\Key::class;
    }
    public function hasKey(string $string)
    {
        return $this->model->where('secret_access', '=', $string)->exists();
    }
}
