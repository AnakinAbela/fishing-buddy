<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fishing_spot_id',
        'species',
        'weight_kg',
        'length_cm',
        'depth_m',
        'photo_path',
        'visibility',
        'notes',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function fishingSpot(){
        return $this->belongsTo(FishingSpot::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }
}
