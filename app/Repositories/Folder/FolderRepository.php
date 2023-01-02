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
        IAwsService $iAwsService
    ) {
        $iAwsService->createFolder($attributes['name'], $attributes['user_id']);
        $this->model->create($attributes);
    }
}
