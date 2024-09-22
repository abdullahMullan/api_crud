<?php

use App\Http\Controllers\API\Authcontroller;
use App\Http\Controllers\API\Postcontroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::post('register', [Authcontroller::class, 'register']);
Route::post('login', [Authcontroller::class, 'login']);
// Route::post('logout', [Authcontroller::class, 'logout'])->middleware('auth:sanctum');
// Route::apiResource('posts',[Postcontroller::class])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [Authcontroller::class, 'logout']);
    Route::apiResource('posts', [Postcontroller::class]);
});
