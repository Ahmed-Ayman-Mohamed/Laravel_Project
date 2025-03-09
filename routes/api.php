<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\DoctorRegisterController;
use App\Http\Controllers\Api\Auth\PatientRegisterController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\PostFolder\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');





// Route::group(['prefix' => 'auth/{role?}'], function () {
//     Route::post('register', [AuthController::class, 'register']);
// });

Route::group(['prefix'=> 'auth'], function () {
    Route::post('patient/register',[PatientRegisterController::class,'register']);
    Route::post('doctor/register',[DoctorRegisterController::class,'register']);
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
});


Route::group(['middleware' => ['jwt_verifier:api']], function () {
    Route::get('users/me', [AuthController::class, 'me']);
    Route::get('doctors',[DoctorController::class,'getAllDoctors']);
    Route::group(['middleware' => 'role_verifier:doctor'],function(){
        Route::get('patients',[DoctorController::class,'getAllPatients']);
    });
    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

