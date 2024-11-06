<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $filable = [
        'media_id',
        'user_id',
        'owner_id',
        'is_approved',
    ];


    // Relasi ke media
    public function file()
    {
        return $this->belongsTo(Media::class, 'file_id');
    }

    // Relas ke user (peminta file)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    // Mengambil data user yang punya file
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
