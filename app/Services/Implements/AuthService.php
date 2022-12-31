<?php

namespace App\Services\Implements;

use App\Repositories\User\IUserRepository;
use App\Services\Interfaces\IAuthService;
use App\Traits\ApiResponse;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;


class AuthService implements IAuthService
{
    use ApiResponse;
    private IUserRepository $UserRepository;

    public function __construct(IUserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }
    public function register(array $data)
    {
        $factory = JWTFactory::customClaims($data);
        $payload = $factory->make();
        return JWTAuth::encode($payload)->get();
    }
    public function confirmRegister(Request $request)
    {
        try {
            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token)->toArray();
        } catch (TokenInvalidException $e) {
            return $this->responseErrorWithData(['token' => 'Invalid token']);;
        } catch (JWTException $e) {
            return $this->responseErrorWithData(['token' => 'Not found']);
        }

        $user = [
            "email" => $apy["email"],
            "password" => Hash::make($apy['password']),
            "username" => $apy["username"],
        ];
        try {
            $this->UserRepository->create($user);
            return true;
        } catch (QueryException) {
            return false;
        }
    }
    public function login(Request $request)
    {
    }
    public function logout(Request $request)
    {
    }
}
