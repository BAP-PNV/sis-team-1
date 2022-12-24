<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Implements\AuthService;
use Validator;


class AuthController extends Controller
{
    private AuthService $auth;

    /**
     * __construct
     *
     * @param  mixed $auth
     * @return void
     */
    public function __construct(AuthService $auth)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
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
            'email' => 'required|email',
            'password' => 'required',
        ]);
        return true;
    }
    /**
     * confirm
     *
     * @param  mixed $request
     * @return void
     */
    public function confirm(Request $request)
    {
        return $this->auth->confirmRegister($request);
    }

    /**
     * register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100',
            'password' => 'required|',
        ]);
        $token = $this->auth->register($validator->validated());
        return response()->json([
            'message' => 'Please confirm via email',
            'token' => $token
        ], 201);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
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
    public function userProfile()
    {
        return response()->json(auth()->user());
    }
}
