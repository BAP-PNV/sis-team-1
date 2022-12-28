<?php
namespace app\Helpers;
use App\Repositories\User\IUserRepository;
use Illuminate\Support\Str;

class SecretKeyHelper
{

    private IUserRepository $userRepository;
    private string $secretKey;

    public function __constructor(IUserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    public function generate()
    {
        return $this->secretKey = Str::uuid();
    }
    public function checkKey(string $string){
        // return $this->u;
    }
    // 368a6084-8aad-4dee-8c1e-6520255c2baa
}
