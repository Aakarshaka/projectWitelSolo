<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SnunitController;
use App\Http\Controllers\SnamController;
use App\Http\Controllers\SnteldaController;
use App\Http\Controllers\TiftaController;
use App\Http\Controllers\TselController;
use App\Http\Controllers\TregController;
use App\Http\Controllers\GsdController;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/warroom', function () {
    return view('warroom');
});

Route::get('supportNeeded/summary', function () {
    return view('supportNeeded.summary');
});

Route::get('supportNeeded/snunit', function () {
    return view('supportNeeded.snunit');
});

Route::get('supportNeeded/sntelda', function () {
    return view('supportNeeded.sntelda');
});

Route::get('supportNeeded/snam', function () {
    return view('supportNeeded.snam');
});

Route::get('eskalasi/gsd', function () {
    return view('eskalasi.gsd');
});

Route::get('eskalasi/tifta', function () {
    return view('eskalasi.tifta');
});

Route::get('eskalasi/treg', function () {
    return view('eskalasi.treg');
});

Route::get('eskalasi/tsel', function () {
    return view('eskalasi.tsel');
});

Route::get('crud/createtelda', function () {
    return view('crud.createtelda');
});

Route::resource('snunit', SnunitController::class);
Route::resource('snunit', SnunitController::class);
Route::resource('snam', SnamController::class);
Route::resource('sntelda', SnteldaController::class);
Route::resource('tifta', TiftaController::class);
Route::resource('treg', TregController::class);
Route::resource('tsel', TselController::class);
Route::resource('gsd', GsdController::class);