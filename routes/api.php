<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// ─── Public routes ────────────────────────────────────────
Route::middleware(['throttle:5,1'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

Route::post('/posts/list',   [PostController::class, 'index']); // JSON body pagination
Route::get('/posts/{post}',  [PostController::class, 'show']);  // single post
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);
    // Create / Update / Delete require auth
    Route::post('/posts',           [PostController::class, 'store']);
    Route::put('/posts/{post}',     [PostController::class, 'update']);
    Route::delete('/posts/{post}',  [PostController::class, 'destroy']);
});