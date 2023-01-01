<?php

namespace App\Services\Implements;

use App\Constants\App;
use App\Repositories\Image\IImageRepository;
use App\Repositories\User\IUserRepository;
use App\Services\Interfaces\IAwsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class AwsS3Service implements IAwsService
{

    private IImageRepository $imageRepository;
    private IUserRepository $userRepository;

    public function __construct(
            IImageRepository $ImageRepository,
            IUserRepository $userRepository
        )
    {
        $this->imageRepository = $ImageRepository;
        $this->userRepository = $userRepository;
    }

    public function index(int $userId, int $folderId)
    {
        return $this->imageRepository->index($userId, $folderId);
    }

    public function create(UploadedFile $file, int $idUser)
    {
        $username = $this->userRepository->find($idUser)->username . "/";

        if (App::STORAGE > (checkStorage($idUser) + convertBtoMB($file->getSize()))) 
        {
            $fileName = time() . '-' . $file->getClientOriginalName();
            Storage::disk('s3')
            ->put('laravel/' . $username . $fileName, file_get_contents($file));
            $path = $this->show('laravel/' . $username . $fileName);
            return $path;
        }

        return App::RETURN_FALSE;
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
