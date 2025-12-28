<?php

namespace App\Http\Controllers;

use App\Models\CatchLog;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(CatchLog $catch)
    {
        $userId = Auth::id();

        $existing = Like::where('user_id', $userId)
            ->where('catch_log_id', $catch->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Unliked';
        } else {
            Like::create([
                'user_id' => $userId,
                'catch_log_id' => $catch->id,
            ]);
            $message = 'Liked';
        }

        return back()->with('success', $message);
    }
}
