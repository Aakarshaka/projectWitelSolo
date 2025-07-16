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
    SumController,
    NewwarroomController,
    NewdashboardController,
    ActivitylogController
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
Route::resource('newwarroom', NewwarroomController::class);
Route::post('/warroom/sync', [NewwarroomController::class, 'syncFromSupportneeded'])->name('warroom.sync');


Route::get('newdashboard', [NewdashboardController::class, 'index'])->name('dashboard');
Route::get('activitylog', [ActivityLogController::class, 'index'])->name('activity-log.index');

//Route::get('dashboard/newdashboard', function () {
  //  return view('dashboard.newdashboard');
//});