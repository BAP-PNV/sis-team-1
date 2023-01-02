<?php

namespace App\Repositories\Folder;

use App\Repositories\Eloquent\BaseRepository;
use App\Services\Interfaces\IAwsService;

class FolderRepository extends BaseRepository implements IFolderRepository
{
    public function getModel()
    {
        return \App\Models\Folder::class;
    }

    public function createFolder(
        $attributes = [],
        IAwsService $iAwsService,
        $upperFolder = 1
    ) {
        $iAwsService->createFolder($attributes['name'], $attributes['user_id'], $upperFolder);
    }

    public function isUserOwesFolder(int $userId, int $upperFolder)
    {
        $result = $this->model
            ->where('user_id', '=', $userId)
            ->where('upper_folder', '=', $upperFolder)
            ->first();
        if ($result) {
            return true;
        }
        return false;
    }
}
