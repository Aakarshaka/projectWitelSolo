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
    // Login routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    // Register routes
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    // OTP routes
    Route::post('/auth/send-otp', [AuthController::class, 'sendOTP'])->name('auth.send-otp');
    Route::post('/auth/verify-otp', [AuthController::class, 'verifyOTP'])->name('auth.verify-otp');
    Route::post('/auth/check-email-verification', [AuthController::class, 'checkEmailVerification'])->name('auth.check-email-verification');
    
    // Alternative register route (untuk kompatibilitas)
    Route::get('auth/register', [AuthController::class, 'showRegister']);
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