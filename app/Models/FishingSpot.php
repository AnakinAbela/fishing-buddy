<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FishingSpot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'country',
        'city',
        'latitude',
        'longitude',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function ($spot) {
            if (empty($spot->slug)) {
                $spot->slug = Str::slug($spot->name . '-' . Str::random(6));
            }
        });
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function catchLogs(){
        return $this->hasMany(CatchLog::class);
    }
}
