<?php
namespace App\Repositories\Image;

use App\Repositories\Interfaces\IRepository;

interface IImageRepository extends IRepository{
    public function calStorage(string $id):float;
}
