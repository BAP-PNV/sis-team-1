<?php

namespace App\Services\Interfaces;
use Illuminate\Http\UploadedFile;

interface IAwsService{
    public function create(UploadedFile $file, int $idUser);
    public function show(string $url);
    public function delete(string $url);  
}
?>
