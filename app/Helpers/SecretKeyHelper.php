<?php
namespace app\Helpers;

use App\Repositories\Key\IKeyRepository;
use App\Repositories\Key\KeyRepository;

class SecretKeyHelper
{

    private static IKeyRepository $secretKey;

    public static function checkKey(string $string){
        return SecretKeyHelper::$secretKey->hasKey($string);
    }
   
}
