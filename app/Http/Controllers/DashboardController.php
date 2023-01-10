<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Http\Resources\FolderResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Interfaces\IAwsService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;
    private IAwsService $awsS3;
    private User $user;

    public function __construct(IAwsService $awsS3)
    {
        $this->awsS3 = $awsS3;
    }

    public function index()
    {

        $userId = auth()->user()->id;
        $files = collect(FileResource::collection($this->awsS3->index($userId, null)));
        $folders = collect(FolderResource::collection($this->awsS3->indexFolder($userId, null)));
        return $this->responseSuccessWithData([
            "files" => $files,
            'folders' => $folders,
            'parent_id' =>  null,
        ]);
    }

    public function getFileAndFolder(Request $request)
    {
        $userId = auth()->user()->id;
        $files = collect(FileResource::collection($this->awsS3->index($userId, $request->id)));
        $folders = collect(FolderResource::collection($this->awsS3->indexFolder($userId, $request->id)));
        return $this->responseSuccessWithData([
            'parent_id' =>  $request->id,
            "files" => $files,
            'folders' => $folders
        ]);
    }

    /**
     * @OA\Get(
     *  path="/api/dashboard/me",
     *  summary="Get the list of resources",
     *  tags={"Profile"},
     *  @OA\Response(response=200, description="Return a list of resources"),
     *  security={{ "apiAuth": {} }}
     * )
     */
    public function me()
    {
        $user = auth()->user();
        $user->store = $this->getImageStorage();
        $userResource = new UserResource($user);
        return $this->responseSuccessWithData($userResource->toArrayUser());
    }

    public function getImageStorage()
    {
        $userId = auth()->user()->id;
        $size = $this->awsS3->imageStorage($userId);
        return $size;
    }
}
