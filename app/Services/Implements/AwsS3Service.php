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

    public function index(int $userId, ?int $folderId)
    {
        if ($folderId == null) {
            $folderId = $this->folderRepository->findUserRootFolder($userId);
        }
        return $this->imageRepository->index($userId, $folderId);
    }

    public function create(UploadedFile $file, int $userId, int $upperFolder)
    {
        $size = convertBtoMB($file->getSize());
        if (AppConstant::STORAGE > (checkStorage($userId) + $size)) {

            if ($upperFolder == AppConstant::ROOT_FOLDER_ID) {
                $upperFolder =  $this->folderRepository->findUserRootFolder($userId);
            }

            $upperPath = reversPath($upperFolder, $this->folderRepository);
            $fileName = time() . '-' . $file->getClientOriginalName();

            $url = AppConstant::ROOT_FOLDER_S3_PATH . $upperPath . $fileName;
            $image = [
                'user_id' => $userId,
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

    public function delete(int $id)
    {
        $image = $this->imageRepository->find($id);

        if ($image == null) {
            return [
                'status' => false,
                'msg' => AppConstant::FILE_NOT_EXIST
            ];
        }

        $url = $image->url;
        if (Storage::disk('s3')->exists($url)) {
            DB::beginTransaction();
            try {
                Storage::disk('s3')->delete($url);
                $this->imageRepository->delete($id);
                DB::commit();
            } catch (\Exception) {
                DB::rollBack();
                return [
                    'status' => false,
                    'msg' => 'can not delete this file'
                ];
            }
            return [
                'status' => true,
                'msg' => 'delete successful'
            ];
        }
    }


    public function indexFolder(int $userId, ?int $folderId)
    {
        if ($folderId == null) {
            $folderId = $this->folderRepository->findUserRootFolder($userId);
        }
        return $this->folderRepository->index($userId, $folderId);
    }

    public function createFolder(string $folderName, int $userId, int $upperFolder)
    {
        if ($upperFolder == AppConstant::ROOT_FOLDER_ID || $this->folderRepository->isUserOwesFolder($userId, $upperFolder)) {
            if ($upperFolder  == AppConstant::ROOT_FOLDER_ID) {
                $upperFolder =  $this->folderRepository->findUserRootFolder($userId);
            }
            $path = reversPath($upperFolder, $this->folderRepository);
            $url = AppConstant::ROOT_FOLDER_S3_PATH  . $path . $folderName;

            if (Storage::disk('s3')->exists($url)) {
                return [
                    'status' => false,
                    'folder' => 'folder existed'
                ];
            } else {

                $folder = [
                    'user_id' => $userId,
                    'upper_folder_id' => $upperFolder,
                    'name' => $folderName
                ];
                DB::beginTransaction();

                try {

                    $folder =  $this->folderRepository->create($folder);
                    Storage::disk('s3')->makeDirectory($url);
                    $folderUrl = Storage::disk('s3')->url($url);
                    DB::commit();
                } catch (\Exception) {

                    DB::rollBack();
                    return [
                        'status' => false,
                        'folder' => 'folder existed'
                    ];
                }
                return [
                    'status' => true,
                    'path' =>  $folderUrl,
                    'id' => $folder['id'],
                    'name' => $folderName
                ];
            }
        }
        return [
            'status' => false,
            'folder' => 'Your key is wrong'
        ];
    }

    public function createFolderRoot(string $folderName, int $userID)
    {
        $url = AppConstant::ROOT_FOLDER_S3_PATH . $folderName;

        if (Storage::disk('s3')->exists($url)) {
            return false;
        } else {
            $folder = [
                'user_id' => $userID,
                'upper_folder_id' => AppConstant::ROOT_FOLDER_ID,
                'name' => $folderName
            ];
            $folder =  $this->folderRepository->create($folder);
            return Storage::disk('s3')->makeDirectory($url);
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


            DB::beginTransaction();

            try {

                foreach (array_reverse($childrenArray) as $key) {
                    $folder =  $this->folderRepository->find($key);
                    $folder->images()->delete();
                    $folder->delete();
                }

                Storage::disk('s3')->deleteDirectory($url);

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
