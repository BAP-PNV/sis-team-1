<?php

namespace App\Services\Implements;

use App\Constants\App;
use App\Repositories\Image\IImageRepository;
use App\Services\Interfaces\IAwsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class AwsS3Service implements IAwsService
{
    private string $id = 'user_01/';
    private IImageRepository $IImageRepo;
    public function __construct(IImageRepository $IImageRepo)
    {
        $this->IImageRepo = $IImageRepo;
    }
    public function create(UploadedFile $file)
    {
        if (App::STORAGE - ($this->checkStorage(3) + $file->getSize()) == 0) {
            $fileName = time() . '-' . $file->getClientOriginalName();
            $path =  Storage::disk('s3')->put('laravel/' . $this->id . $fileName, file_get_contents($file));
            $path = $this->show('laravel/' . $this->id . $fileName);
            return $path;
        }
        return -1;
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
    public function checkStorage(int $id)
    {
        return $this->IImageRepo->calStorage($id);
    }
}
