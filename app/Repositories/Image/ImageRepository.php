<?php

namespace App\Repositories\Image;

use App\Repositories\Eloquent\BaseRepository;

class ImageRepository extends BaseRepository implements IImageRepository
{

    public function getModel()
    {
        return 3;
    }

    public function calStorage(string $key): float
    {
        return $this->model->where('user_id', '=', $key)->sum('size');
    }
}
