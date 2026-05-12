<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; // Tambahkan ini

class User extends Authenticatable implements JWTSubject // Tambahkan implements
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'exp_points',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- FUNGSI WAJIB JWT ---
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // --- RELASI DATABASE ---
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function organizedCampaigns()
    {
        return $this->hasMany(Campaign::class, 'organizer_id');
    }

    public function joinedCampaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_user')
                    ->withPivot('attendance')
                    ->withTimestamps();
    }
}