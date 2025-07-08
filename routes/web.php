<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    SnunitController,
    SnamController,
    SnteldaController,
    TiftaController,
    TselController,
    TregController,
    GsdController,
    WarroomController,
    SummaryController,
    WitelController
};

// ==================
// Auth Routes
// ==================
Route::view('/', 'auth.login');
Route::view('/auth/login', 'auth.login')->name('login');
Route::view('/auth/register', 'auth.register')->name('register');

// ==================
// Dashboard
// ==================
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::view('/warroom', 'warroom')->name('warroom');

// ==================
// Support Needed
// ==================
Route::get('supportNeeded/summary', [SummaryController::class, 'index'])->name('summary.index');

// ==================
// Resource Controllers (CRUD)
// ==================
Route::resource('snunit', SnunitController::class);
Route::resource('snam', SnamController::class);
Route::resource('sntelda', SnteldaController::class);
Route::resource('tifta', TiftaController::class)->parameters(['tifta' => 'tifta']);
Route::resource('treg', TregController::class);
Route::resource('tsel', TselController::class);
Route::resource('gsd', GsdController::class);
Route::resource('warroom', WarroomController::class);
Route::resource('witel', WitelController::class);

Route::get('/supportneeded', function () {
    return view('supportneeded');
});

Route::get('/supportneeded2', function () {
    return view('supportneeded2');
});

Route::get('/supportneeded3', function () {
    return view('supportneeded3');
});