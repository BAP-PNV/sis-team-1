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

    public function create(UploadedFile $file, int $idUser, int $upperFolder)
    {
        $size = convertBtoMB($file->getSize());
        if (AppConstant::STORAGE > (checkStorage($idUser) + $size)) {

            $upperPath = reversPath($upperFolder, $this->folderRepository);
            $fileName = time() . '-' . $file->getClientOriginalName();
            $url = AppConstant::ROOT_FOLDER_S3_PATH . $upperPath . $fileName;
            $image = [
                'user_id' => $idUser,
                'folder_id' => $upperFolder,
                'url' => $url,
                'size' => $size
            ];

            DB::beginTransaction();

            try {

                $this->imageRepository->create($image);
                Storage::disk('s3')->put($url, file_get_contents($file));
                DB::commit();
                return  $this->show($url);
            } catch (\Exception) {
                DB::rollBack();
                return AppConstant::RETURN_FALSE;
            }
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


    public function indexFolder(int $userId, int $upperFolderId)
    {
        if ($upperFolderId == AppConstant::ROOT_FOLDER_ID) {
            $upperFolderId = $this->folderRepository->findUserRootFolder($userId);
        }
        return $this->folderRepository->index($userId, $upperFolderId);
    }

    public function createFolder(string $folderName, int $userId, int $upperFolder)
    {
        if (
            $upperFolder != AppConstant::ROOT_FOLDER_ID &&
            !$this->folderRepository->isUserOwesFolder($userId, $upperFolder)
        ) {
            return [
                'folder' => 'Your key is wrong'
            ];
        }

        $path = reversPath($upperFolder, $this->folderRepository);
        $url = AppConstant::ROOT_FOLDER_S3_PATH  . $path . $folderName;

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
        if (Storage::disk('s3')->exists(AppConstant::ROOT_FOLDER_S3_PATH  . $folderName)) {
            $folder = Storage::disk('s3')->allDirectories(AppConstant::ROOT_FOLDER_S3_PATH  . $folderName);
            return $folder;
        } else {
            return false;
        }
    }



    public function deleteFolder($id)
    {
        $folder = $this->folderRepository->find($id);

        $upperFolderId = $folder->upper_folder_id;
        $path = reversPath($upperFolderId, $this->folderRepository);

        $url = AppConstant::ROOT_FOLDER_S3_PATH  . $path . $folder->name;

        if (Storage::disk('s3')->exists($url)) {

            $childrenArray = getChildren($folder);

            // Add current folder to list children to remove all include current folder

            array_unshift($childrenArray, $id);
            Storage::disk('s3')->deleteDirectory($url);

            DB::beginTransaction();

            try {

                foreach (array_reverse($childrenArray) as $key) {
                    $this->folderRepository->delete($key);
                }

                DB::commit();
                return AppConstant::RETURN_TRUE;
            } catch (\Exception) {
                DB::rollBack();
                return AppConstant::CAN_NOT_DELETE;
            }
        } else {
            return AppConstant::FOLDER_NOT_EXIST;
        }
    }
}
