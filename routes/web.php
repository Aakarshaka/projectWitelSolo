<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/warroom', function () {
    return view('warroom');
});

Route::get('/summary', function () {
    return view('summary');
});