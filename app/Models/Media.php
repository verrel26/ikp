<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Media extends Model
{
    use HasFactory;
    protected $table = "medias";
    protected $fillable = [
        'file',
        'user_id',
        'type',
        'file_path',
        'status_izin',
    ];


    // Relasi User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi share file
    public function shared_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'media_user', 'media_id', 'user_id')
            ->withTimestamps();
    }
}
