<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{

    public function store(Request $req)
    {
        $req->validate([
          'title'       => 'required|string',
          'authors'     => 'nullable|string',
          'description' => 'nullable|string',
          'cover'       => 'nullable|image|max:2048',
          'file'        => 'required|mimes:pdf,epub|max:10240',
        ]);

        $user = $req->user();

        // store cover
        $coverPath = $req->cover
           ? $req->cover->store('books/covers','public')
           : null;

        // store PDF/EPUB
        $filePath = $req->file->store('books/files','public');

        $book = Book::create([
          'user_id'     => $user->id,
          'title'       => $req->title,
          'authors'     => $req->authors,
          'description' => $req->description,
          'cover_path'  => $coverPath,
          'file_path'   => $filePath,
        ]);

        return response()->json([
          'message'=>'Book uploaded',
          'book'   =>$book
        ],201);
    }

    public function index(Request $req)
    {
      // optionally filter by user or show all
      $books = Book::with('user')->latest()->paginate(20);
      return response()->json($books);
    }
}
