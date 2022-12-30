<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Symfony\Component\Translation\t;

class User extends Model
{
    use HasFactory;
    protected $table = 'users';
    public function profile()
    {
        return $this->hasOne(Profile::class,'userId','id');
    }
    public function key()
    {
        return $this->hasOne(Key::class);
    }
    public function folders()
    {
        return $this->hasMany(Folder::class);
    }
}
