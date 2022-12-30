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
        $User = $this->model->where('secret_access_key', '=', $string)->first();
        
        if (is_null($User)) {
            return -1;
        }

        return $User->id;
    }

}
