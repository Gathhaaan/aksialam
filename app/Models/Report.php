<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'location_name',
        'latitude',
        'longitude',
        'status',
        'image_url', // Jangan lupa kolom gambar yang baru kita buat
    ];

    // Laporan milik 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Laporan bisa dijadikan 1 campaign
    public function campaign()
    {
        return $this->hasOne(Campaign::class);
    }
}