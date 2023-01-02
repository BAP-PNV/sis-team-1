<?php

namespace App\Services\Implements;

use App\Constants\AppConstant;
use App\Repositories\Folder\IFolderRepository;
use App\Repositories\Image\IImageRepository;
use App\Repositories\User\IUserRepository;
use App\Services\Interfaces\IAwsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AwsS3Service implements IAwsService
{

    private IImageRepository $imageRepository;
    private IUserRepository $userRepository;
    private IFolderRepository $folderRepository;

    public function __construct(
        IImageRepository $ImageRepository,
        IUserRepository $userRepository,
        IFolderRepository $folderRepository
    ) {
        $this->imageRepository = $ImageRepository;
        $this->userRepository = $userRepository;
        $this->folderRepository = $folderRepository;
    }

    public function index(int $userId, int $folderId)
    {
        return $this->imageRepository->index($userId, $folderId);
    }

    public function create(UploadedFile $file, int $idUser)
    {
        $username = $this->userRepository->find($idUser)->username . "/";

        if (AppConstant::STORAGE > (checkStorage($idUser) + convertBtoMB($file->getSize()))) {
            $fileName = time() . '-' . $file->getClientOriginalName();
            Storage::disk('s3')
                ->put(AppConstant::ROOT_FOLDER_S3_PATH . $username . $fileName, file_get_contents($file));
            $path = $this->show(AppConstant::ROOT_FOLDER_S3_PATH . $username . $fileName);
            return $path;
        }

        return AppConstant::RETURN_FALSE;
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

    public function createFolder(string $folderName, int $userId, int $upperFolder)
    {
        $username = $this->userRepository->find($userId)->username . "/";
        $path = reversPath($upperFolder, $this->folderRepository);
        $url = AppConstant::ROOT_FOLDER_S3_PATH . $username . $path . $folderName;
        if (Storage::disk('s3')->exists($url)) {
            return false;
        } else {
            $folder = [
                'user_id' => $userId,
                'upper_folder_id' => $upperFolder,
                'name' => $folderName
            ];
            DB::beginTransaction();
            try {
                $this->folderRepository->create($folder);
                Storage::disk('s3')->makeDirectory($url);
                $folder = Storage::disk('s3')->url($url);
                DB::commit();
                return $folder;
            } catch (\Exception) {
                DB::rollBack();
                return false;
            }
        }
    }

    public function showFolder(string $folderName)
    {
        if (Storage::disk('s3')->exists(AppConstant::ROOT_FOLDER_S3_PATH . $this->idFolder . $folderName)) {
            $folder = Storage::disk('s3')->allDirectories(AppConstant::ROOT_FOLDER_S3_PATH . $this->idFolder . $folderName);
            return $folder;
        } else {
            return false;
        }
    }

    public function deleteFolder(string $folderName, int $userId)
    {
        $username = $this->userRepository->find($userId)->username . "/";

        $url = AppConstant::ROOT_FOLDER_S3_PATH . $username . $folderName;
        if (Storage::disk('s3')->exists($url)) {
            $status = Storage::disk('s3')->deleteDirectory($url);
            return $status;
        } else {
            return false;
        }
    }
}
