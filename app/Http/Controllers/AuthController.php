<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

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

        // Tentukan field login (email atau username)
        $loginField = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        // Cari user berdasarkan email atau username
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
            ->withErrors(['login' => 'Username/Email atau Password salah'])
            ->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('message', 'Logout berhasil');
    }
}