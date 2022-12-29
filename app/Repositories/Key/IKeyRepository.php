<?php

namespace App\Repositories\Key;

use App\Repositories\Interfaces\IRepository;

interface IKeyRepository extends IRepository
{
    public function hasKey(string $string);
}
