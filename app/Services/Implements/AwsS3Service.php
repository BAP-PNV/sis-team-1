<?php

namespace App\Services\Implements;

use App\Services\Interfaces\IAwsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class AwsS3Service implements IAwsService
{
    private string $id = 'user_01/';
    public function create(UploadedFile $file)
    {
        $fileName = time() . '-' . $file->getClientOriginalName();
        $path =  Storage::disk('s3')->put('laravel/' . $this->id . $fileName, file_get_contents($file));
        $path = $this->show('laravel/' . $this->id . $fileName);
        return $path;
    }
    public function show(string $url)
    {
        $file = Storage::disk('s3')->url($url);
        return $file;
    }
    public function delete(string $url)
    {
        if (Storage::disk('s3')->exists($url)) {
            $status = Storage::disk('s3')->delete($url);
            return $status;
        } else {
            return "not found";
        }
    }
    private string $idFolder = 'user_02/';

    public function createFolder(string $folderName)
    {
        if (Storage::disk('s3')->exists('laravel/' . $this->idFolder . $folderName)) {
            return false;
        } else {
            Storage::disk('s3')->makeDirectory('laravel/' . $this->idFolder . $folderName);
            $folder = Storage::disk('s3')->url($folderName);
            return $folder;
        }
    }
    public function showFolder(string $folderName)
    {
        if (Storage::disk('s3')->exists('laravel/' . $this->idFolder . $folderName)) {
            $folder = Storage::disk('s3')->allDirectories('laravel/' . $this->idFolder . $folderName);
            return $folder;
        } else {
            return false;
        }
    }
    public function deleteFolder(string $folderName)
    {
        if (Storage::disk('s3')->exists('laravel/' . $this->idFolder . $folderName)) {
            $status = Storage::disk('s3')->deleteDirectory('laravel/' . $this->idFolder . $folderName);
            return $status;
        } else {
            return false;
        }
    }
}
