<?php

namespace App\Http\Resources;

use App\Constants\AppConstant;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return   [
            'username' => $this->username,
            'email' => $this->email,
            'secret_key' => $this->key->secret_access_key,
        ];
    }
    public function toArrayUser()
    {
        return   [
            'username' => $this->username,
            'email' => $this->email,
            'secret_key' => $this->key->secret_access_key,
            'storage_total' => AppConstant::STORAGE,
            'storage' => 0
        ];
    }
}
