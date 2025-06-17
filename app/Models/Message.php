<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'message',
    //     'sender_id',
    //     'group_id',
    //     'receiver_id',
    //     'conversation_id'
    // ];

    protected $fillable = [
        'conversation_id',
        'user_id',
        'body',
        'voice_path',
        'voice_duration',
    ];

    // public function sender()
    // {
    //     return $this->belongsTo(User::class, 'sender_id');
    // }

    // public function receiver()
    // {
    //     return $this->belongsTo(User::class, 'receiver_id');
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function group()
    // {
    //     return $this->belongsTo(Group::class);
    // }

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
