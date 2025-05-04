<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
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
    Route::post('books', [BookController::class,'store']);
    Route::get('books',  [BookController::class,'index']);
});
