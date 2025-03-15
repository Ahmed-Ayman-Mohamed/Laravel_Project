<?php

use App\Http\Controllers\UserFolder\UserController;
use App\Http\Controllers\PostFolder\PostController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('welcome');


// Route::namespace('UserFolder')->group(function () {
//     Route::get('users', [UserController::class, 'show'])->name('user');
//     Route::get('users/show', [UserController::class, 'showUsers']);
// });

// Route::controller(PostController::class)->group(function () {
//     Route::get('posts', 'index')->name('post');
//     Route::get('posts/show', 'showPosts');
//     Route::post('posts/insert', 'insert')->name('post.insert');
//     Route::get('post/edit/{id}', 'edit')->name('post.edit');
// });
