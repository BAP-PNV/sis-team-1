<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'upper_folder_id',
        'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function children()
    {
        return $this->hasMany(Folder::class, 'upper_folder_id');
    }

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'upper_folder_id');
    }
}
