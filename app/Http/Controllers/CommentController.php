<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'catch_log_id' => 'required|exists:catch_logs,id',
            'content' => 'required|string|max:500',
        ]);

        $data['user_id'] = Auth::id();

        Comment::create($data);

        return back()->with('success', 'Comment added!');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Comment deleted!');
    }
}