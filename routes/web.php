<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    SupportneededController,
    SumController,
    NewwarroomController,
    NewdashboardController,
    ActivitylogController,
    AuthController
};

// ==================
// Public Routes (Accessible to all)
// ==================
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('newdashboard');
    }
    return redirect()->route('login');
})->name('home');

// ==================
// Auth Routes (Guest Only)
// ==================
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ==================
// Authenticated Routes
// ==================
Route::middleware(['auth'])->group(function () {
    // Logout route - menggunakan POST untuk keamanan
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard route
    Route::get('/newdashboard', [NewdashboardController::class, 'index'])->name('newdashboard');
    
    // Support needed routes
    Route::get('/supportneeded/detail', [SupportneededController::class, 'getDetail'])->name('supportneeded.detail');
    Route::resource('supportneeded', SupportneededController::class);
    
    // Summary routes
    Route::resource('newsummary', SumController::class);
    
    // Warroom routes
    Route::resource('newwarroom', NewwarroomController::class);
    Route::post('/warroom/sync', [NewwarroomController::class, 'syncFromSupportneeded'])->name('warroom.sync');
    
    // Activity log routes
    Route::get('/activitylog', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/activity-log/{log}/details', [ActivitylogController::class, 'getLogDetails'])->name('activity-log.details');
});