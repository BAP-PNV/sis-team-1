<?php

namespace app\Helpers;

use App\Repositories\Key\KeyRepository;

class SecretKeyHelper
{

    private static KeyRepository $secretKey;

    public static function checkKey(string $string)
    {
        SecretKeyHelper::$secretKey = new KeyRepository;
        return SecretKeyHelper::$secretKey->hasKey($string);
    }
}
