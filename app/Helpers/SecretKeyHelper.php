<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Repositories\Key\KeyRepository;

class SecretKeyHelper
{

    private static KeyRepository $secretKey;

    public static function checkKey(string $string):int
    {
        SecretKeyHelper::load();
        return SecretKeyHelper::$secretKey->hasKey($string);
    }

    public static function generate()
    {
        $key = Str::random(20);
        SecretKeyHelper::load();
        return $key;
    }
    
    private static function load()
    {
        SecretKeyHelper::$secretKey = new KeyRepository;
    }
}
