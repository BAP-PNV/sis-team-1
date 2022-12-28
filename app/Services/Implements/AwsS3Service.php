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
}
