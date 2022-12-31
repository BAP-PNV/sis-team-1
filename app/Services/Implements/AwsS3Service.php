<?php

namespace App\Services\Implements;

use App\Constants\App;
use App\Repositories\User\IUserRepository;
use App\Services\Interfaces\IAwsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class AwsS3Service implements IAwsService
{

    private IUserRepository $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create(UploadedFile $file, int $idUser)
    {
        $username = $this->userRepository->find($idUser)->username."/";
 
        if (App::STORAGE > (checkStorage($idUser) + convertBtoMB($file->getSize()))) {
            $fileName = time() . '-' . $file->getClientOriginalName();
            Storage::disk('s3')->put('laravel/' . $username . $fileName, file_get_contents($file));
            $path = $this->show('laravel/' . $username . $fileName);
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
}
