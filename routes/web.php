<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\BookcatController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [BookcatController::class, 'loginform'])->name('loginform');

Route::post('/login', [BookcatController::class, 'loginProcess'])->name('login');