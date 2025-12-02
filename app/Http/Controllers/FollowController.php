<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggle(User $user)
    {
        $authUser = Auth::user();

        if ($authUser->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        $follow = Follow::where('follower_id', $authUser->id)
                        ->where('followed_id', $user->id)
                        ->first();

        if ($follow) {
            $follow->delete();
            $message = 'Unfollowed';
        } else {
            Follow::create([
                'follower_id' => $authUser->id,
                'followed_id' => $user->id
            ]);
            $message = 'Following';
        }

        return back()->with('success', $message);
    }
}