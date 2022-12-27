<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use Illuminate\Http\Request;
use App\Services\Implements\AuthService;
use Validator;

// use function Psy\debug;

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
        $this->middleware('auth:api', ['except' => ['login', 'register','confirm']]);
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
        if (! $token = auth()->attempt($validator->validated())) {
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
        return $this->auth->confirmRegister($request);
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
