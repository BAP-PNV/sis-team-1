<?php

namespace App\Services\Implements;

use App\Constants\AppConstant;
use App\Helpers\SecretKeyHelper;
use App\Mail\AccountInfo;
use App\Repositories\Key\IKeyRepository;
use App\Repositories\User\IUserRepository;
use App\Services\Interfaces\IAuthService;
use App\Traits\ApiResponse;
use App\Repositories\Folder\IFolderRepository;
use App\Services\Interfaces\IAwsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;


class AuthService implements IAuthService
{
    use ApiResponse;

    private IUserRepository $UserRepository;
    private IKeyRepository $keyRepository;
    private IFolderRepository $folderRepository;
    private IAwsService $iAWsService;

    public function __construct(
        IUserRepository $UserRepository,
        IKeyRepository $keyRepository,
        IFolderRepository $folderRepository,
        IAwsService $iAWsService
    ) {
        $this->UserRepository = $UserRepository;
        $this->keyRepository = $keyRepository;
        $this->folderRepository = $folderRepository;
        $this->iAWsService = $iAWsService;
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
            $folder = [
                'user_id' => $user->id,
                'upper_folder_id' => AppConstant::ROOT_FOLDER_ID,
                'name' => $user->username
            ];

            $this->folderRepository->createFolderRoot($folder, $this->iAWsService);
            $this->keyRepository->create($key);
            $user['password'] = $apy['password'];
            $user['secret_access_key'] = $user->key->secret_access_key;
            Mail::to($user->email)->send(new AccountInfo($user));

            DB::commit();
            return [
                'status' => true,
                'token' => auth()->login($user),
                'user' => "user create successful",
                'username' => $user['username']
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => false,
                "user" => "please check the email"
            ];
        }
    }

    public function login(Request $request)
    {
    }
    public function logout(Request $request)
    {
    }
}
