<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Symfony\Component\Translation\t;

class User extends Model
{
    use HasFactory;
    protected $table = 'users';
    public function profiles(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Profile::class,'userId','id');
    }
}
