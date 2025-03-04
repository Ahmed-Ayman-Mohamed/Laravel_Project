<?php

use App\Http\Controllers\Api\Auth\DoctorAuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\PostFolder\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');





Route::group(['prefix' => 'doctor/auth'], function () {
    Route::post('register', [DoctorAuthController::class, 'register']);
});

Route::group(['prefix' => 'doctor/auth'], function () {
    Route::post('login', [DoctorAuthController::class, 'login']);
});



Route::group(['prefix' => 'doctor', 'middleware' => ['doctor_middleware:doctor_api']], function () {
    Route::get('me', [DoctorController::class, 'me']);

    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout', [DoctorAuthController::class, 'logout']);
    });
});

Route::group(['prefix' => 'doctor', 'middleware' => ['doctor_middleware:api']], function () {
    Route::get('posts', [PostController::class, 'getAllPosts']);
});
