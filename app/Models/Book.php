<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'authors',
        'description',
        'cover_path',
        'file_path'
    ];

     /**
     * The user who uploaded this book.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
