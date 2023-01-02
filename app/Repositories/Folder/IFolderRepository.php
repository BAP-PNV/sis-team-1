<?php

namespace App\Repositories\Folder;

use App\Repositories\Interfaces\IRepository;
use App\Services\Interfaces\IAwsService;

interface IFolderRepository extends IRepository
{
    public function createFolder($attributes = [], IAwsService $iAwsService);
    public function isUserOwesFolder(int $userId, int $upperFolder);
}
