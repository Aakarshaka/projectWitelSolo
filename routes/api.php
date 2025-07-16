<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Dummy route untuk menghindari error
Route::get('/ping', function () {
    return response()->json(['pong' => true]);
});
