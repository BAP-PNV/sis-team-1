<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Implements\AuthService;
use App\Traits\ApiResponse;
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
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // return $this->createNewToken($token);
        // return redirect('/');
    }
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * confirm
     *
     * @param  mixed $request
     * @return void
     */
    public function confirm(Request $request)
    {
        if ($this->auth->confirmRegister($request)) {
            return $this->responseSuccessWithData(["user" => "create new user successful"], 201);
        }
        return $this->responseErrorWithData(["user" => "please check the email"]);;
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
        return response()->json([
            'message' => 'Please confirm via email',
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
    public function userProfile()
    {
        return response()->json(auth()->user());
    }
}
