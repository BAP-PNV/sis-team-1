<?php

namespace App\Repositories\Folder;

use App\Constants\AppConstant;
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
        if ($this->model->find($upperFolder)->user_id == $userId) {
            return true;
        }
        return false;
    }

    public function index(int $userId, int $upperFolder)
    {
        $folder = $this->model->where('user_id', '=', $userId)->find($upperFolder);
        if ($folder) {
            return $folder->children()->get();
        }
        return false;
    }

    public function findUserRootFolder(int $userId)
    {
        return $this->model
            ->where('user_id', '=', $userId)
            ->where('upper_folder_id', '=', AppConstant::ROOT_FOLDER_ID)
            ->first()->id;
    }
}
