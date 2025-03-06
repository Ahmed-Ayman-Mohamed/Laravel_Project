<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\PostFolder\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');





Route::group(['prefix' => 'auth/{role?}'], function () {
    Route::post('register', [AuthController::class, 'register']);
});

Route::group(['prefix' => 'auth/{role?}'], function () {
    Route::post('login', [AuthController::class, 'login']);
});



Route::group(['prefix' => 'doctor', 'middleware' => ['jwt_verifier:api', 'role_verifier:doctor']], function () {
    Route::get('me', [DoctorController::class, 'me']);

    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::group(['middleware' => ['jwt_verifier:api', 'role_verifier:doctor']], function () {
    Route::get('posts', [PostController::class, 'getAllPosts']);
});
