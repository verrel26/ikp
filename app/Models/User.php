<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // satu user memiliki/bisa upload lebih dari 1 file
    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function shared_media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'media_user', 'user_id', 'media_id')
            ->withTimestamps();
    }
}
