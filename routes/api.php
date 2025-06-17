<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageAttachmentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/signin', [AuthController::class, 'signin']);

// Protected endpoints (user must be authenticated via Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/signout', [AuthController::class, 'signout']);

    //Profile Endpoint
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::patch('/profile', [ProfileController::class, 'updateProfile']);
    Route::post('/profile/photo', [ProfileController::class, 'updateProfilePhoto']);
    Route::delete('/profile/photo', [ProfileController::class, 'deleteProfilePhoto']);

    // Books Endpoint
    Route::post('books', [BookController::class, 'store']);
    Route::get('books',  [BookController::class, 'index']);

    // Conversation Endpoint
    // Route::middleware('auth:api')->group(function () {
    Route::get('/conversations',[ConversationController::class, 'index']);
    Route::post('/conversations',[ConversationController::class, 'store']);
    // Group
    Route::get('/groups',                   [GroupController::class, 'index']);
    Route::post('/groups',                   [GroupController::class, 'store']);
    Route::post('/groups/{group}/users',     [GroupController::class, 'addUser']);
    // Messages
    Route::get('/conversations/{conv}/messages',[MessageController::class, 'index']);
    Route::post('/conversations/{conv}/messages',[MessageController::class, 'store']);
    // Attachments
    Route::get('/attachments/{id}',         [MessageAttachmentController::class, 'show']);
    // });

    // Posts
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);



    Route::middleware('auth:api')->group(function () {
        Route::post('posts/{post}/like', [LikeController::class, 'toggle']);
    });
    Route::get('posts/{post}/comments', [CommentController::class, 'index']);
    Route::post('posts/{post}/comments', [CommentController::class, 'store']);


    Route::get('/users', [UserController::class, 'index']);
});
