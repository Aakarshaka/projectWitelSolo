<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('newdashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('newdashboard');
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Tentukan field login (email atau name)
        $loginField = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        // Cari user berdasarkan email atau name
        $user = User::where($loginField, $request->username)->first();

        if ($user) {
            // Cek apakah password sudah di-hash atau belum
            if (Hash::needsRehash($user->password)) {
                // Password belum di-hash, cek dengan plain text
                if ($user->password === $request->password) {
                    // Update password dengan hash yang benar
                    $user->password = Hash::make($request->password);
                    $user->save();

                    // Login user
                    Auth::login($user);
                    $request->session()->regenerate();

                    return redirect()->route('newdashboard');
                }
            } else {
                // Password sudah di-hash, gunakan Auth::attempt
                $loginData = [
                    $loginField => $request->username,
                    'password' => $request->password
                ];

                if (Auth::attempt($loginData)) {
                    $request->session()->regenerate();
                    return redirect()->route('newdashboard');
                }
            }
        }

        return redirect()->back()
            ->withErrors(['login' => 'Username/Email atau Password salah, Silahkan coba lagi'])
            ->withInput($request->only('username'));
    }

    public function showRegister()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('newdashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('newdashboard');
        }

        // Debug: Log input data - TAMBAHKAN INI UNTUK DEBUGGING
        \Log::info('Registration attempt - Raw input:', $request->all());
        \Log::info('Role value received:', ['role' => $request->role]);

        // Cek apakah email sudah diverifikasi
        $verificationKey = 'email_verified_' . $request->email;
        $isEmailVerified = Cache::get($verificationKey, false);

        if (!$isEmailVerified) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email address first'
            ], 400);
        }

        // Daftar role yang diizinkan
        $allowedRoles = [
            'TELDA BLORA', 'TELDA BOYOLALI', 'TELDA JEPARA', 'TELDA KLATEN', 
            'TELDA KUDUS', 'MEA SOLO', 'TELDA PATI', 'TELDA PURWODADI', 
            'TELDA REMBANG', 'TELDA SRAGEN', 'TELDA WONOGIRI', 'BS', 
            'GS', 'PRQ', 'SSGS', 'LESA V', 'RSO WITEL','Magang'
        ];

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:255|unique:users,name|regex:/^[a-zA-Z0-9_]+$/', // Validasi username, cek unique di kolom name
            'email' => 'required|string|email|max:255|unique:users,email',
            'role' => ['required', 'string', Rule::in($allowedRoles)], // Validasi role
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).*$/'
            ],
            'confirmPassword' => 'required|string|same:password',
        ], [
            'username.required' => 'Username is required',
            'username.min' => 'Username must be at least 3 characters long',
            'username.unique' => 'Username already exists',
            'username.regex' => 'Username can only contain letters, numbers, and underscores',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'Email already exists',
            'role.required' => 'Unit/Role is required',
            'role.in' => 'Selected unit/role is not valid',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters long',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number',
            'confirmPassword.required' => 'Please confirm your password',
            'confirmPassword.same' => 'Passwords do not match',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // PERBAIKAN: Pastikan role tersimpan dengan benar
            $userData = [
                'name' => $request->username, // Simpan username ke kolom name
                'email' => $request->email,
                'role' => $request->role, // Pastikan ini mengambil role dari request
                'password' => Hash::make($request->password),
                'email_verified_at' => now(), // Karena sudah verifikasi OTP
            ];

            // Debug log sebelum create user
            \Log::info('User data to be created:', $userData);

            // Buat user baru
            $user = User::create($userData);

            // Debug log setelah user dibuat
            \Log::info('User created successfully:', [
                'user_id' => $user->id, 
                'email' => $user->email, 
                'role' => $user->role,
                'name' => $user->name
            ]);

            // Hapus cache verifikasi email
            Cache::forget($verificationKey);

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Registration failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // ... sisanya tetap sama seperti kode asli ...

    public function sendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email'
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'Email already exists'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Generate OTP 6 digit
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan OTP ke cache dengan expiry 5 menit
        $cacheKey = 'otp_' . $request->email;
        Cache::put($cacheKey, $otp, 300); // 5 menit

        try {
            // Kirim email OTP
            $this->sendOTPEmail($request->email, $otp);

            // Log OTP untuk development (hapus di production)
            \Log::info("OTP for {$request->email}: {$otp}");

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your email',
                // 'otp' => $otp // Hapus ini di production
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ], 500);
        }
    }

    /**
     * Kirim email OTP ke user
     */
    private function sendOTPEmail($email, $otp)
    {
        try {
            Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Email Verification - GIAT CORE')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
        } catch (\Exception $e) {
            \Log::error('Mail sending failed:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $cacheKey = 'otp_' . $request->email;
        $storedOTP = Cache::get($cacheKey);

        if (!$storedOTP) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired. Please request a new one.'
            ], 400);
        }

        if ($storedOTP !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.'
            ], 400);
        }

        // OTP valid, hapus dari cache
        Cache::forget($cacheKey);

        // Simpan status verifikasi email
        $verificationKey = 'email_verified_' . $request->email;
        Cache::put($verificationKey, true, 600); // 10 menit untuk menyelesaikan registrasi

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully!'
        ]);
    }

    public function checkEmailVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'verified' => false
            ]);
        }

        $verificationKey = 'email_verified_' . $request->email;
        $isVerified = Cache::get($verificationKey, false);

        return response()->json([
            'success' => true,
            'verified' => $isVerified
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('message', 'Logout berhasil');
    }

    // ... sisanya kode untuk forget password tetap sama ...
    
    public function showForgetPassword()
    {
        if (Auth::check()) {
            return redirect()->route('newdashboard');
        }

        return view('auth.forgetpass');
    }

    public function sendForgetPasswordOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.exists' => 'Email not found in our records'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $cacheKey = 'fpotp_' . $request->email;
        Cache::put($cacheKey, $otp, 600);

        try {
            $this->sendForgetPasswordOTPEmail($request->email, $otp);
            \Log::info("Forget Password OTP for {$request->email}: {$otp}");

            return response()->json([
                'success' => true,
                'message' => 'Password reset code sent successfully to your email'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send forget password OTP:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reset code. Please try again.'
            ], 500);
        }
    }

    public function verifyForgetPasswordOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $cacheKey = 'fpotp_' . $request->email;
        $storedOTP = Cache::get($cacheKey);

        if (!$storedOTP) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired. Please request a new one.'
            ], 400);
        }

        if ($storedOTP !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.'
            ], 400);
        }

        Cache::forget($cacheKey);

        $resetToken = Str::random(60);
        $resetTokenKey = 'password_reset_token_' . $request->email;
        Cache::put($resetTokenKey, $resetToken, 900);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully!',
            'reset_token' => $resetToken
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).*$/'
            ],
            'password_confirmation' => 'required|string|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $resetTokenKey = 'password_reset_token_' . $request->email;
        $storedToken = Cache::get($resetTokenKey);

        if (!$storedToken || $storedToken !== $request->token) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired reset token. Please request a new password reset.'
            ], 400);
        }

        try {
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            Cache::forget($resetTokenKey);
            \Log::info("Password reset successfully for user: {$request->email}");

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully! You can now login with your new password.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Password reset failed:', ['error' => $e->getMessage(), 'email' => $request->email]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password. Please try again.'
            ], 500);
        }
    }

    public function resendForgetPasswordOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $cacheKey = 'fpotp_' . $request->email;
        Cache::put($cacheKey, $otp, 600);

        try {
            $this->sendForgetPasswordOTPEmail($request->email, $otp);
            \Log::info("Resend Forget Password OTP for {$request->email}: {$otp}");

            return response()->json([
                'success' => true,
                'message' => 'New password reset code sent successfully to your email'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to resend forget password OTP:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reset code. Please try again.'
            ], 500);
        }
    }

    private function sendForgetPasswordOTPEmail($email, $otp)
    {
        try {
            Mail::send('emails.fpotp', ['otp' => $otp], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Password Reset Code - GIAT CORE')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
        } catch (\Exception $e) {
            \Log::error('Forget password OTP mail sending failed:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}