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
        $iAwsService->createFolder($attributes['name']);
        $this->model->create($attributes);
    }
}
