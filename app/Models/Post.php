<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Post.php

class Post extends Model
{
    protected $fillable = ['user_id', 'content', 'book_link'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // to quickly count likes on a post:
    public function likedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
