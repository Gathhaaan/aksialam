<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'points_required', 'stock', 'image_url'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_rewards')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
