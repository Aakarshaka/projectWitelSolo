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

Route::get('/supportneeded/detail', [SupportneededController::class, 'getDetail'])->name('supportneeded.detail');

//Route::resource('warroom', WarroomController::class);
Route::resource('witel', WitelController::class);
Route::resource('supportneeded', SupportneededController::class);

Route::resource('newsummary', SumController::class);

Route::get('dashboard/newdashboard', function () {
    return view('dashboard.newdashboard');
});

Route::get('warroom/newwarroom', function () {
    return view('warroom.newwarroom');
});