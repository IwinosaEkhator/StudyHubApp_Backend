<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        return response()->json(
            $post->comments()->with('user:id,username,profile_photo')->latest()->get()
        );
    }

    public function store(Request $req, Post $post)
    {
        $req->validate(['body' => 'required|string|max:1000']);
        $comment = $post->comments()->create([
            'user_id' => $req->user()->id,
            'body' => $req->body
        ]);
        // return the newly created comment with its user
        return response()->json(
            $comment->load('user'),
            201
        );
    }
}
