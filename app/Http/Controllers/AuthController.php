<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use App\Mail\Registered;
use Illuminate\Http\Request;
use App\Services\Implements\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Mail;
use Validator;

class AuthController extends Controller
{
    use ApiResponse;
    private AuthService $auth;

    /**
     * __construct
     *
     * @param  mixed $auth
     * @return void
     */
    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle user login by email or username
        $token = auth()->attempt($validator->validated()) ?: auth()->attempt([
            'username' => $validator->validated()['email'],
            'password' => $validator->validated()['password']
        ]);

        if (!$token) {
            return $this->responseErrorUnauthorized();
        }
        return $this->respondWithToken($token);
    }

    /**
     * confirm
     *
     * @param  mixed $request
     * @return void
     */
    public function confirm(Request $request)
    {
        $result = $this->auth->confirmRegister($request);
        if ($result['status']) {
            return $this->responseSuccessWithData($result, 201);
        }
        return $this->responseErrorWithData($result);;
    }

    /**
     * register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(SignUpRequest $request)
    {
        $validator = $request->validated();
        $token = $this->auth->register($validator);
        Mail::to($validator['email'])->send(new Registered($token));
        //        return $validator['email'];
        return response()->json([
            'message' => $validator['email'],
            'token' => $token
        ], 201);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
}
