<?php

use App\Models\Folder;
use App\Repositories\Folder\IFolderRepository;
use App\Repositories\Image\ImageRepository;

if (!function_exists('checkStorage')) {
    function checkStorage(int $id)
    {
        $imageRepo = new ImageRepository();
        return $imageRepo->calStorage($id);
    }
}

if (!function_exists('convertBtoMB')) {
    function convertBtoMB(float $byte)
    {
        return $byte / 1000000;
    }
}

if (!function_exists('reversPath')) {
    function reversPath(int $upperFolder, IFolderRepository $iFolderRepository)
    {
        $path = "";

        if ($upperFolder != 1) {

            while ($upperFolder > 1) {

                $folder = $iFolderRepository->find($upperFolder);
                $path .= $folder->name . '/';
                $upperFolder = $folder->upper_folder_id;
            }

            $reversed = array_reverse(explode('/', $path));
            $path = "";

            // Start at 2 to remove user folder

            for ($i = 1; $i < sizeof($reversed); $i++) {
                $path .= $reversed[$i] . '/';
            }
        }
        return $path;
    }
}

if (!function_exists('getChildren')) {
    function getChildren($folder)
    {
        $ids = [];
        if ($folder->children) {
            foreach ($folder->children as $fol) {
                $ids[] = $fol->id;
                $ids = array_merge($ids, getChildren($fol));
            }
        }
        return $ids;
    }
}

if (!function_exists('checkUserOwnedFolder')) {
    function checkUserOwnedFolder(int $userId, int $folderId): bool
    {
        $folder = new Folder();;
        if ($folder->find($folderId)->user_id == $userId) {
            return true;
        }
        return false;
    }
}
