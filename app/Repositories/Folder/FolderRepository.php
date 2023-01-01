<?php
namespace App\Repositories\Folder;
use App\Repositories\Eloquent\BaseRepository;

class FolderRepository extends BaseRepository implements IFolderRepository
{
    public function getModel()
    {
        return \App\Models\Folder::class;
    }
}
