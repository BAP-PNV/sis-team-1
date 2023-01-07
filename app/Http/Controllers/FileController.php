<?php

namespace App\Http\Controllers;

use App\Constants\AppConstant;
use App\Http\Resources\FileResource;
use App\Http\Resources\FolderResource;
use App\Services\Implements\AwsS3Service;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class FileController extends Controller
{
    use ApiResponse;
    private AwsS3Service $awsS3;

    public function __construct(AwsS3Service $awsS3)
    {
        $this->awsS3 = $awsS3;
    }

    public function index(Request $request)
    {
        $files = collect(FileResource::collection($this
            ->awsS3
            ->index($request->user_id, $request->id ?: null)));

        return $this->responseSuccessWithData($files->toArray());;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $path = $this->awsS3->create($file, $request->user_id, $request->id ?: AppConstant::ROOT_FOLDER_ID);

            if ($path != -1) {
                return $this->responseSuccessWithData(['path' => $path], 201);
            }

            return $this->responseErrorWithData(
                ["storage" => "not enough storage space"]
            );
        }

        return $this->responseErrorWithData(["image" => "Not found"]);;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $results = $this->awsS3->delete($id);

        if (!$results['status']) {
            $this->responseErrorWithData(['file' => $results['msg']]);
        }
        return $this->responseSuccessWithData(['file' => $results['msg']]);
    }


    public function indexFolder(Request $request)
    {
        $folders = $this->awsS3->indexFolder($request->user_id, $request->id ?: AppConstant::ROOT_FOLDER_ID);
        if ($folders) {
            $foldersArr =  collect(FolderResource::collection($folders));
            return  $this->responseSuccessWithData([
                'parent_id' => $request->id ?: null,
                'folders' =>  $foldersArr
            ]);
        };
        return $this->responseErrorWithData(['permission' => 'You can not access this folder']);
    }


    public function destroyFolder(Request $request)
    {
        if ($request->has('folderName')) {
            $folderName = $request->get('folderName');
            $status = $this->awsS3->deleteFolder($folderName, $request->user_id);
            if ($status) {
                return $this->responseSuccess(200);
            }
            return $this->responseErrorWithData(['folder' => 'Not exist'], 401);
        }
        return $this->responseErrorWithData(['folder' => 'Not found']);
    }

    public function createFolder(Request $request)
    {
        if ($request->has('folderName')) {

            $folderName = $request->input('folderName');
            $upperFolderId = $request->id ?: AppConstant::ROOT_FOLDER_ID;
            $results = $this->awsS3->createFolder($folderName, $request->user_id, $upperFolderId);

            if ($results['status']) {
                return $this->responseSuccessWithData($results, 201);
            } else {
                return $this->responseErrorWithData($results, 400);
            }
        }
        return $this->responseErrorWithData(['param' => 'Not found'], 401);
    }

    public function deleteFolder(Request $request)
    {
        $responseArray = [
            AppConstant::CAN_NOT_DELETE =>
            $this->responseErrorWithData(['folder' => AppConstant::CAN_NOT_DELETE]),
            AppConstant::FOLDER_NOT_EXIST =>
            $this->responseErrorWithData(['folder' => AppConstant::FOLDER_NOT_EXIST]),
            AppConstant::RETURN_TRUE =>
            $this->responseSuccessWithData(['folder' => 'delete successful'])
        ];

        $response = $this->awsS3->deleteFolder($request->id);
        return $responseArray[$response];
    }
}
