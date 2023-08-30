<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/login', [App\Http\Controllers\API\AuthController::class , 'loginUser'])->name('login');
    Route::apiResource('users', App\Http\Controllers\API\UserController::class);
    Route::apiResource('posts', App\Http\Controllers\API\PostController::class);
    Route::apiResource('comments', App\Http\Controllers\API\CommentController::class);
});

Route::post('auth/register', [App\Http\Controllers\API\AuthController::class , 'registerUser'])->name('register');