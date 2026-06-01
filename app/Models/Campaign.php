<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id', 'report_id', 'title', 'description', 'event_date', 
        'max_volunteers', 'target_metric', 'metric_unit', 
        'image_after_path', 'status'
    ];

    // Di-organize oleh 1 user
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    // Berasal dari 1 laporan (bisa null)
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    // Punya banyak relawan (users)
    public function volunteers()
    {
        return $this->belongsToMany(User::class, 'campaign_user')
                    ->withPivot('attendance')
                    ->withTimestamps();
    }
}