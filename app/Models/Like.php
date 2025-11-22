<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'catch_log_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function catchLog(){
        return $this->belongsTo(CatchLog::class);
    }
}
