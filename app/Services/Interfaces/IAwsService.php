<?php

namespace App\Services\Interfaces;

use Illuminate\Http\UploadedFile;

interface IAwsService
{
    public function create(UploadedFile $file, int $idUser, int $upperFolder);
    public function show(string $url);
    public function delete(int $id);
    public function createFolder(string $folderName, int $userID, int $upperFolder);
    public function showFolder(string $folderName);
    public function deleteFolder(int $id);
}
