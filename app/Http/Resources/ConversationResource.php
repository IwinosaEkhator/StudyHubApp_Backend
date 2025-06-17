<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // App\Http\Resources\ConversationResource.php

    public function toArray($request)
    {
        $meId = $request->user()->id;

       // find the “other” user in the participants list:
    $other = $this->users->first(fn($u) => $u->id !== $meId);

        // the eager-loaded last message (may be null)
        $last = $this->lastMessage;


        // $unreadCount = $this->messages()
        //     ->where('receiver_id', $me)
        //     ->whereNull('read_at')
        //     ->count();

        return [
            'id'             => $this->id,
            'other_user'     => [
                'id'           => $other->id,
                'username'     => $other->username,
                'profile_photo'=> $other->profile_photo,
            ],
            'last_message'   => $last
                ? $last->body
                : null,
            'last_time'      => $last
                ? $last->created_at->format('H:i')
                : null,          // or ->toDateTimeString() or ->diffForHumans()
            // 'unread_count'   => $unreadCount,
        ];
    }
}
