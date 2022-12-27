<?php

namespace App\Services\Interfaces;
use Illuminate\Http\UploadedFile;

interface IAwsService{
    public function create(UploadedFile $file);
    public function show(string $url);
}
?>
