<?php

use App\Http\Controllers\AuthController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Auth
Route::post('/v1/auth/register', [AuthController::class, 'register']);
Route::post('/v1/auth/login', [AuthController::class, 'login']);
Route::post('/v1/auth/verifyauth', [AuthController::class, 'verifyAuth']);
Route::post('/v1/auth/logout', [AuthController::class, 'logout']);
Route::post('/v1/auth/oauth', [AuthController::class, 'oauth']);
