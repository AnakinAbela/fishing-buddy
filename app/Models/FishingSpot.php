<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FishingSpot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'latitude',
        'longitude',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function catchLogs(){
        return $this->hasMany(CatchLog::class);
    }
}
