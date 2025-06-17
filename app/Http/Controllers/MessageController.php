<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    // // Fetch messages in conversation or group
    // public function index(Request $request)
    // {
    //     $data = $request->validate([
    //         'conversation_id' => 'nullable|exists:conversations,id',
    //         'group_id' => 'nullable|exists:groups,id',
    //     ]);

    //     // 2) Build the query, eager‐loading sender + attachments
    //     $query = Message::with('sender', 'attachments')
    //         ->orderBy('created_at', 'asc');

    //     // 3) Filter by conversation or group
    //     if (!empty($data['conversation_id'])) {
    //         $query->where('conversation_id', $data['conversation_id']);
    //     } elseif (!empty($data['group_id'])) {
    //         $query->where('group_id', $data['group_id']);
    //     } else {
    //         // neither ID provided → bad request
    //         return response()->json([
    //             'error'   => 'validation_error',
    //             'message' => 'You must supply conversation_id or group_id'
    //         ], 422);
    //     }

    //     // 4) Execute & return
    //     $messages = $query->get();

    //     return response()->json($messages);
    // }

    // // Send a new message
    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'message' => 'nullable|string',
    //         'conversation_id' => 'nullable|exists:conversations,id',
    //         'group_id' => 'nullable|exists:groups,id',
    //         'attachments.*' => 'file|max:10240'
    //     ]);

    //     $msg = Message::create([
    //         'message' => $data['message'] ?? null,
    //         'sender_id' => $request->user()->id,
    //         'conversation_id' => $data['conversation_id'] ?? null,
    //         'group_id' => $data['group_id'] ?? null,
    //     ]);

    //     // handle attachments
    //     if ($request->hasFile('attachments')) {
    //         foreach ($request->file('attachments') as $file) {
    //             $path = $file->store('attachments');
    //             MessageAttachment::create([
    //                 'message_id' => $msg->id,
    //                 'name' => $file->getClientOriginalName(),
    //                 'path' => $path,
    //                 'mime' => $file->getClientMimeType(),
    //                 'size' => $file->getSize(),
    //             ]);
    //         }
    //     }

    //     // Debug: is the model filled?
    //     // Log::info('Message created:', $msg->toArray());
    //     // dd($msg->toArray());  // temporarily halt to inspect

    //     // broadcast to everyone else in this conversation
    //     broadcast(new MessageSent($msg))->toOthers();

    //     return response()->json($msg->load('sender'), 201);
    // }

    // GET /api/conversations/{conv}/messages
    public function index(Request $request, $convId)
    {
        return Message::with('user')
            ->where('conversation_id', $convId)
            ->get();
    }

    // POST /api/conversations/{conv}/messages
    public function store(Request $req, $convId)
    {

        Log::info('Incoming message payload', [
            'conversation_id' => $convId,
            'body'            => $req->input('body'),
            'user'            => $req->user()->id,
          ]);

          $data = $req->validate([
            'body'      => 'nullable|string',
            'image'     => 'nullable|file|image|max:10240',
            'file'      => 'nullable|file|max:10240',
            'audio'     => 'nullable|file|mimes:mp3,wav|max:10240',
            'video'     => 'nullable|file|mimetypes:video/mp4,video/quicktime|max:20480',
            'book_link' => 'nullable|url',
          ]);

        $msg = Message::create([
            'conversation_id' => $convId,
            'user_id'         => $req->user()->id,
            'body'            => $data['body'] ?? null,
            'book_link'       => $data['book_link'] ?? null,
        ]);

        if ($req->hasFile('image')) {
            $path = $req->file('image')->store('chat_images');
            $msg->attachments()->create(['path'=>$path,'type'=>'image']);
          }

          if ($req->hasFile('audio')) {
            $path = $req->file('audio')->store('chat_audios');
            $msg->attachments()->create(['path'=>$path,'type'=>'audio']);
          }
          if ($req->hasFile('file')) {
            $path = $req->file('file')->store('chat_files');
            $msg->attachments()->create(['path'=>$path,'type'=>'file']);
          }
          if ($req->hasFile('video')) {
            $path = $req->file('video')->store('chat_videos');
            $msg->attachments()->create(['path'=>$path,'type'=>'video']);
          }
        // broadcast via Echo
        broadcast(new MessageSent($msg->load('user')));

        return response()->json($msg->load('user'), 201);
    }
}
