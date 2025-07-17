<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    SupportneededController,
    SumController,
    NewwarroomController,
    NewdashboardController,
    ActivitylogController,
    LoginController
};

// ==================
// Auth Routes
// ==================
// Route login tetap tanpa middleware
Route::view('/', 'auth.login');
Route::view('/auth/login', 'auth.login')->name('login');
Route::get('/login/{provider}/redirect', [LoginController::class, 'redirect'])->name('login.redirect');
Route::get('/login/{provider}/callback', [LoginController::class, 'callback'])->name('login.callback');

// Group route yang butuh login
Route::middleware(['auth'])->group(function () {
    Route::get('/supportneeded/detail', [SupportneededController::class, 'getDetail'])->name('supportneeded.detail');
    Route::resource('supportneeded', SupportneededController::class);
    Route::resource('newsummary', SumController::class);
    Route::resource('newwarroom', NewwarroomController::class);
    Route::post('/warroom/sync', [NewwarroomController::class, 'syncFromSupportneeded'])->name('warroom.sync');
    Route::get('newdashboard', [NewdashboardController::class, 'index'])->name('dashboard');
    Route::get('activitylog', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/activity-log/{log}/details', [ActivitylogController::class, 'getLogDetails'])->name('activity-log.details');
});

//Route::get('dashboard/newdashboard', function () {
  //  return view('dashboard.newdashboard');
//});