<?php

namespace App\Services\Implements;

use App\Repositories\User\IUserRepository;
use App\Services\Interfaces\IAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;


class AuthService implements IAuthService
{
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

        $token = JWTAuth::getToken();
        $apy = JWTAuth::getPayload($token)->toArray();
        $user = [
            "email" => $apy["email"],
            "password" => Hash::make($apy['password']),
            "name" =>  $apy["name"],
        ];
        $result = $this->UserRepository->create($user);
        return  $result;
    }
    public function login(Request $request)
    {
    }
    public function logout(Request $request)
    {
    }
}
