<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    protected $table = 'users';
    public function profiles(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this -> hasOne(Profile::class,'userId','id');
    }
//    public function folders(): \Illuminate\Database\Eloquent\Relations\HasMany
//    {
//        return $this -> hasMany(Folder::class, 'ownerId','id');
//    }
}
