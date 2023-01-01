<?php

namespace App\Http\Controllers;

use App\Services\Implements\AwsS3Service;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    use ApiResponse;
    private AwsS3Service $awsS3;

    public function __construct(AwsS3Service $awsS3)
    {
        $this->awsS3 = $awsS3;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            $path = $this->awsS3->create($file, $request->user_id);

            if ($path != -1) {
                return response()->json([
                    'message' => 'success',
                    'path' => $path
                ], 201);
            }

            return $this->responseErrorWithData(["Storage" => "not enough storage space"]);
        }

        return $this->responseErrorWithData(["image" => "Not found"]);;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return  $this->awsS3->show('laravel/user_01/1672123409-VNP_PHP_INTERN.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status =  $this->awsS3->delete('laravel/user_01/' . '1672131286-VNP_PHP_INTERN.pdf');
        if ($status) {
            return $status;
        }
        return 0;
    }
    public function destroyFolder(Request $request)
    {
        $folderName = $request->get('folderName');
        $status = $this->awsS3->deleteFolder($folderName);
        if ($status) {
            return $this->responseSuccess(200);
        }
        return $this->responseErrorWithData(['folder' => 'Not exist'], 401);
    }
    public function createFolder(Request $request)
    {
        if ($request->has('folderName')) {
            $folderName = $request->input('folderName');
            $path = $this->awsS3->createFolder($folderName);
            if ($path) {
                return $this->responseSuccessWithData(['folder' => $path], 201);
            } else {
                return $this->responseErrorWithData(['folder' => 'folder existed'], 400);
            }
        }
        return $this->responseErrorWithData(['param' => 'Not found'], 401);
    }
    public function showFolder(Request $request)
    {
        $path = $this->awsS3->showFolder('$folderName');
        if ($path) {
            return $this->responseSuccessWithData(['folders' => $path], 201);
        } else {
            return $this->responseErrorWithData(['folder' => 'Not exist'], 401);
        }
    }
}
