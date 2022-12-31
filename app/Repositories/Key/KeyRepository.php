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
        $Key = $this->model->where('secret_access_key', '=', $string)->first();

        if (is_null($Key)) {
            return -1;
        }

        return $Key->user_id;
    }
}
