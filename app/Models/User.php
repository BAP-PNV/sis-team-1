<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User  extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;

    protected $table = 'users';
    protected $fillable = ['email', 'password', 'username'];

    public function profile()
    {
        return $this->hasOne(Profile::class, 'userId', 'id');
    }

    public function key()
    {
        return $this->hasOne(Key::class);
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    
}
