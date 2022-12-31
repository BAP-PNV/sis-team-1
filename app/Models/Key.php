<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    use HasFactory;
    protected $table = 'api_keys';
    protected $fillable =  ['user_id', 'secret_access_key'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
