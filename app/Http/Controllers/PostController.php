<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user')
            ->withCount(['comments', 'likes'])
            ->latest()
            ->get();

        return response()->json($posts);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $user = $request->user(); // now works

        $post = Post::create([
            'user_id' => $user->id,
            'content' => $request->content,
            'book_link' => $request->book_link,
        ]);

        return response()->json($post->load('user'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json($post->load('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post); // Optional: policy-based protection

        $validated = $request->validate([
            'content' => 'required|string|max:280',
            'book_link' => 'nullable|url',
        ]);

        $post->update($validated);

        return response()->json($post->load('user'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post); // Optional: only owner/admin can delete

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully.']);
    }
}
