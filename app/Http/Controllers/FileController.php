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

    /**
     * @OA\Get(
     *     path="/api/files",
     *     operationId="index",
     *     summary="Get all files ",
     *     tags={"Files"},
     *     description="get all file",
     *     @OA\Parameter(
     *          name="access_key",
     *          description="api key",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          
     *       ),
     *       @OA\Response(
     *          response=400, description="Error",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

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
    /**
     * @OA\Post(
     *     path="/api/file",
     *     summary="Get all files and folders",
     *     operationId="create",
     *     tags={"Files"},
     *     description="get all file and folder",
     *     @OA\Parameter(
     *          name="access_key",
     *          description="api key",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="file to upload",
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                 ),
     *                 required={"image"}
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function create(Request $request)
    {
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $path = $this->awsS3->create($file, $request->user_id, $request->id ?: AppConstant::ROOT_FOLDER_ID);

            if ($path != -1) {
                return $this->responseSuccessWithData(
                    [
                        'url' => $path,
                        'name' => getFileName($path)
                    ],
                    201
                );
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

        /**
     * @OA\Delete(
     *     path="/api/file/{id}",
     *     operationId="destroy",
     *     summary="delete folders",
     *     tags={"Files"},
     *     description="get all file and folder",
     *     @OA\Parameter(
     *          name="access_key",
     *          description="api key",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="key to delete folder",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          
     *       ),
     *       @OA\Response(
     *          response=400, description="Error",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function destroy($id)
    {
        $results = $this->awsS3->delete($id);

        if (!$results['status']) {
            $this->responseErrorWithData(['file' => $results['msg']]);
        }
        return $this->responseSuccessWithData(['file' => $results['msg']]);
    }


    /**
     * @OA\Get(
     *     path="/api/folders",
     *     operationId="indexFolder",
     *     summary="Get folders",
     *     tags={"Folders"},
     *     description="get all file and folder",
     *     @OA\Parameter(
     *          name="access_key",
     *          description="api key",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          
     *       ),
     *       @OA\Response(
     *          response=400, description="Error",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function indexFolder(Request $request)
    {
        $folders = $this->awsS3->indexFolder($request->user_id, $request->id ?: null);
        if ($folders) {
            $foldersArr =  collect(FolderResource::collection($folders));
            return  $this->responseSuccessWithData([
                'parent_id' => $request->id ?: null,
                'folders' =>  $foldersArr
            ]);
        };
        return $this->responseErrorWithData(['permission' => 'You can not access this folder']);
    }

    /**
     * @OA\Post(
     *     path="/api/folder",
     *     summary="create new folder",
     *     operationId="createFolder",
     *     tags={"Folders"},
     *     description="get all file and folder",
     *     @OA\Parameter(
     *          name="access_key",
     *          description="api key",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="folderName",
     *          description="name of folder",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
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


    /**
     * @OA\Delete(
     *     path="/api/folder/{id}",
     *     operationId="deleteFolder",
     *     summary="delete folders",
     *     tags={"Folders"},
     *     description="get all file and folder",
     *     @OA\Parameter(
     *          name="access_key",
     *          description="api key",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="key to delete folder",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          
     *       ),
     *       @OA\Response(
     *          response=400, description="Error",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
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
