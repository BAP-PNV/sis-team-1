<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';
    protected $fillable = [
        'user_id',
        'size',
        'folder_id',
        'url'
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
