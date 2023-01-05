<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Http\Resources\FolderResource;
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

    public function setModel(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $this->setModel(auth()->user());
        $userId = $this->user->id;
        $files = collect(FileResource::collection($this->awsS3->index($userId, null)));
        $folders = collect(FolderResource::collection($this->awsS3->indexFolder($userId, null)));
        return $this->responseSuccessWithData([
            "files" => $files,
            'folders' => $folders
        ]);
    }

    public function me(Request $request)
    {
        return $this->responseSuccessWithData(['data' => $request->user_id]);;
    }
}
