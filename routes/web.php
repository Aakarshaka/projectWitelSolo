<?php

use Illuminate\Support\Facades\Route;
use App\Exports\SupportneededExport;
use App\Exports\NewwarroomExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
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
Route::get('/supportneeded/export', function () {
    return Excel::download(new SupportneededExport, 'supportneeded.xlsx');
})->name('supportneeded.export');

Route::get('/newwarroom/export', function () {
    return Excel::download(new NewwarroomExport, 'newwarroom.xlsx');
})->name('newwarroom.export');

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
// FORGET PASSWORD ROUTES
// ==================
// Tampilkan form forget password
Route::get('/forgetpass', [AuthController::class, 'showForgetPassword'])->name('forgetpass');

// Kirim OTP ke email
Route::post('/forget-password/send-otp', [AuthController::class, 'sendForgetPasswordOTP'])->name('forget-password.send-otp');

// Verifikasi OTP (akan generate reset_token)
Route::post('/auth/verify-forget-password-otp', [AuthController::class, 'verifyForgetPasswordOTP'])->name('auth.verify-forget-password-otp');

// Reset password dengan reset_token
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password');

// Kirim ulang OTP
Route::post('/auth/resend-forget-password-otp', [AuthController::class, 'resendForgetPasswordOTP'])->name('auth.resend-forget-password-otp');

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
    Route::resource('supportneeded', SupportneededController::class)->except(['show']);


    // Summary routes
    Route::resource('newsummary', SumController::class);

    // Warroom routes
    Route::resource('newwarroom', NewwarroomController::class);
    Route::post('/warroom/sync', [NewwarroomController::class, 'syncFromSupportneeded'])->name('warroom.sync');
    Route::get('/newwarroom/{newwarroom}/action-plans', [NewwarroomController::class, 'getActionPlans'])
        ->name('newwarroom.action-plans');

    // Activity log routes
    Route::get('/activitylog', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/activity-log/{log}/details', [ActivitylogController::class, 'getLogDetails'])->name('activity-log.details');

});
