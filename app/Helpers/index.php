<?php

use App\Repositories\Image\ImageRepository;

if (!function_exists('checkStorage')) {
    function checkStorage(int $id)
    {
        $imageRepo = new ImageRepository();
        return $imageRepo->calStorage($id);
    }
}

if (!function_exists('convertBtoMB')) {
    function convertBtoMB(float $byte)
    {
        return $byte / 1000000;
    }
}
