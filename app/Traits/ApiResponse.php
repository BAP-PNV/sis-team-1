<?php

namespace App\Traits;

trait ApiResponse
{
    public function response(array $data = [], string $message = "", int $status = 200)
    {
        return response()->json(
            [
                'message' => $message,
                'data' => $data
            ],
            $status
        );
    }
    public function responseSuccess(int $status = 200)
    {
        return $this->response([], 'success', $status);
    }
    public function responseSuccessWithData(array $data = [], int $status = 200)
    {
        return $this->response($data, 'success', $status);
    }
    public function responseError(int $status = 400)
    {
        return $this->response([], 'error', $status);
    }
    public function responseErrorWithData(array $data = [], int $status = 400)
    {
        return $this->response($data, 'error', $status);
    }
    public function responseErrorUnauthorized(int $status = 401)
    {
        return $this->response([],"Unauthorized",$status);
    }
};
