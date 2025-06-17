<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{


    // List all conversations for authenticated user
    // public function index(Request $request)
    // {
    //     $userId = $request->user()->id;
    //     $conversations = Conversation::where('user_id1', $userId)
    //         ->orWhere('user_id2', $userId)
    //         ->get();

    //     return ConversationResource::collection($conversations);
    // }

    // // Start a new one-on-one conversation
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'participant_id' => 'required|exists:users,id',
    //     ]);

    //     $userId = $request->user()->id;
    //     $other = $request->participant_id;

    //     $conversation = Conversation::firstOrCreate(
    //         ['user_id1' => min($userId, $other), 'user_id2' => max($userId, $other)],
    //         ['user_id1' => min($userId, $other), 'user_id2' => max($userId, $other)]
    //     );

    //     return response()->json($conversation, 201);
    // }


    // GET /api/conversations
    public function index(Request $request)
    {
        return $request->user()
                       ->conversations()
                       ->with(['users:id,username,profile_photo'])
                       ->get();
    }

    // POST /api/conversations
    public function store(Request $request)
    {
        $request->validate([
            'participant_id' => 'required|exists:users,id',
        ]);

        $me = $request->user()->id;
        $other = $request->participant_id;

        // find existing or create new
        $conv = Conversation::whereHas('users', fn($q)=> $q->where('user_id',$me))
            ->whereHas('users', fn($q)=> $q->where('user_id',$other))
            ->first();

        if (!$conv) {
            $conv = Conversation::create();
            $conv->users()->attach([$me, $other]);
        }

        return response()->json($conv->load('users'));
    }
}
