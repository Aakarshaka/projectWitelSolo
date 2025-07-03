<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SnunitController;
use App\Http\Controllers\SnamController;
use App\Http\Controllers\SnteldaController;
use App\Http\Controllers\TiftaController;
use App\Http\Controllers\TselController;
use App\Http\Controllers\TregController;
use App\Http\Controllers\GsdController;
use App\Http\Controllers\WarroomController;
use App\Http\Controllers\SummaryController;

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

Route::get('form/createtelda', function () {
    return view('form.createtelda');
});

Route::get('supportNeeded/sntelda', [SnteldaController::class, 'index'])->name('sntelda.index');
Route::get('supportNeeded/snunit', [SnunitController::class, 'index'])->name('snunit.index');
Route::get('supportNeeded/snam', [SnamController::class, 'index'])->name('snam.index');
Route::get('eskalasi/treg', [TregController::class, 'index'])->name('treg.index');
Route::get('eskalasi/tsel', [TselController::class, 'index'])->name('tsel.index');
Route::get('eskalasi/tifta', [TiftaController::class, 'index'])->name('tifta.index');
Route::get('eskalasi/gsd', [GsdController::class, 'index'])->name('gsd.index');
Route::get('supportNeeded/summary', [SummaryController::class, 'index'])->name('summary.index');

Route::resource('snunit', SnunitController::class);
Route::resource('snam', SnamController::class);
Route::resource('sntelda', SnteldaController::class);
Route::resource('tifta', TiftaController::class);
Route::resource('treg', TregController::class);
Route::resource('tsel', TselController::class);
Route::resource('gsd', GsdController::class);
Route::resource('warroom', WarroomController::class);