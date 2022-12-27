<?php

namespace App\Http\Controllers;

use App\Services\Implements\AwsS3Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
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
            $path = $this->awsS3->create($file);
            return response()->json([
                'message'=>'success',
                'path'=> $path
            ],201);
        }
        return response()->json([
            'data'=>'fail'
        ],412);  
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
        $status =  $this->awsS3->delete('laravel/user_01/'.'1672131286-VNP_PHP_INTERN.pdf');
        if ($status) {
            return $status;
        }
        return 0;
    }
}
