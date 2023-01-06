<?php

namespace App\Repositories\Folder;

use App\Repositories\Interfaces\IRepository;
use App\Services\Interfaces\IAwsService;

interface IFolderRepository extends IRepository
{
    public function createFolder($attributes = [], IAwsService $iAwsService, int  $upperFolder);

    public function isUserOwesFolder(int $userId, int $folderId);

    public function index(int $userId, int  $upperFolder);

    public function findUserRootFolder(int $userId);

    public function createFolderRoot($attributes = [], IAwsService $iAwsService);
}
