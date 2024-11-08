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

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DENIED = 'denied';


    // Relas ke user (peminta file)
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // Relasi ke media
    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
