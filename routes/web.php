<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});


Route::view('/login', 'auth.login');
Route::view('/register', 'auth.register');

Route::middleware('auth.check')->group(function () {
    Route::view('/users', 'users.index');
});
