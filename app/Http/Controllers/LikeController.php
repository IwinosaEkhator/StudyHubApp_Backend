<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $req, Post $post)
    {
        $user = $req->user();

        // if already liked, remove; otherwise add
        if ($post->likedBy($user)) {
            $post->likes()->where('user_id', $user->id)->delete();
            return response()->json(['liked' => false, 'count' => $post->likes()->count()]);
        }

        $post->likes()->create(['user_id' => $user->id]);
        return response()->json(['liked' => true, 'count' => $post->likes()->count()]);
    }
}
