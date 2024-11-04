<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = "medias";
    protected $fillable = [
        'file',
        'user_id',
        'type',
        'file_path',
        'status_izin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
