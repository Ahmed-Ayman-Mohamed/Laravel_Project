<?php

use App\Http\Controllers\UserFolder\UserController;
use App\Http\Controllers\PostFolder\PostController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('welcome');
