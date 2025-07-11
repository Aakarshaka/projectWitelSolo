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
    WitelController,
    SupportneededController,
    SumController
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
Route::get('/supportneeded/detail', [SupportneededController::class, 'getDetail'])->name('supportneeded.detail');

Route::resource('snunit', SnunitController::class);
Route::resource('snam', SnamController::class);
Route::resource('sntelda', SnteldaController::class);
Route::resource('tifta', TiftaController::class)->parameters(['tifta' => 'tifta']);
Route::resource('treg', TregController::class);
Route::resource('tsel', TselController::class);
Route::resource('gsd', GsdController::class);
//Route::resource('warroom', WarroomController::class);
Route::resource('witel', WitelController::class);
Route::resource('supportneeded', SupportneededController::class);

Route::get('summary/newsummary', function () {
    return view('summary.newsummary');
});
Route::get('summary/newsummary', [SumController::class, 'index']);

Route::get('dashboard/newdashboard', function () {
    return view('dashboard.newdashboard');
});

Route::get('warroom/newwarroom', function () {
    return view('warroom.newwarroom');
});