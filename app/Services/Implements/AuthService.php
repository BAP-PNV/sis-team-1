<?php

namespace App\Services\Implements;

use App\Helpers\SecretKeyHelper;
use App\Repositories\Key\IKeyRepository;
use App\Repositories\User\IUserRepository;
use App\Services\Interfaces\IAuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;


class AuthService implements IAuthService
{
    use ApiResponse;
    private IUserRepository $UserRepository;
    private IKeyRepository $keyRepository;
    public function __construct(IUserRepository $UserRepository, IKeyRepository $keyRepository)
    {
        $this->UserRepository = $UserRepository;
        $this->keyRepository = $keyRepository;
    }
    public function register(array $data)
    {
        $factory = JWTFactory::customClaims($data);
        $payload = $factory->make();
        return JWTAuth::encode($payload)->get();
    }
    public function confirmRegister(Request $request)
    {

        $apy = $request->apy;
        $userInfo = [
            "email" => $apy["email"],
            "password" => Hash::make($apy['password']),
            "username" => $apy["username"],
        ];
        DB::beginTransaction();
        try {
            $user = $this->UserRepository->create($userInfo);
            $key = [
                "user_id" => $user->id,
                'secret_access_key' => SecretKeyHelper::generate()
            ];
            $this->keyRepository->create($key);
            DB::commit();
            return true;
        } catch (\Exception) {
            DB::rollBack();
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
