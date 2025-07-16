<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Redirect ke penyedia (Google atau Microsoft)
     */
    public function redirect($provider)
    {
        try {
            // Validasi provider yang didukung
            if (!in_array($provider, ['google', 'microsoft'])) {
                return redirect()->route('login')->withErrors([
                    'msg' => 'Provider login tidak didukung.'
                ]);
            }

            // Konfigurasi khusus untuk Microsoft
            if ($provider === 'microsoft') {
                return Socialite::driver($provider)
                    ->scopes(['openid', 'profile', 'email'])
                    ->redirect();
            }

            // Untuk Google dan provider lainnya
            return Socialite::driver($provider)->redirect();

        } catch (\Exception $e) {
            Log::error('Error pada redirect OAuth: ' . $e->getMessage(), [
                'provider' => $provider,
                'exception' => $e
            ]);

            return redirect()->route('login')->withErrors([
                'msg' => 'Terjadi kesalahan saat mengarahkan ke ' . ucfirst($provider) . '.'
            ]);
        }
    }

    /**
     * Callback dari penyedia
     */
    public function callback($provider)
    {
        try {
            // Validasi provider yang didukung
            if (!in_array($provider, ['google', 'microsoft'])) {
                return redirect()->route('login')->withErrors([
                    'msg' => 'Provider login tidak didukung.'
                ]);
            }

            // Ambil data user dari Microsoft/Google
            $socialUser = Socialite::driver($provider)->user();

            // Validasi apakah data user berhasil didapat
            if (!$socialUser) {
                Log::error('Social user data kosong', ['provider' => $provider]);
                return redirect()->route('login')->withErrors([
                    'msg' => 'Gagal mengambil data dari ' . ucfirst($provider) . '.'
                ]);
            }

            // Log data user untuk debugging (hapus setelah selesai development)
            Log::info('Social user data:', [
                'provider' => $provider,
                'id' => $socialUser->getId(),
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'avatar' => $socialUser->getAvatar()
            ]);

            // Email dari akun sosial
            $email = $socialUser->getEmail();
            if (!$email) {
                Log::warning('Email tidak ditemukan dari social user', [
                    'provider' => $provider,
                    'social_id' => $socialUser->getId()
                ]);
                
                return redirect()->route('login')->withErrors([
                    'msg' => 'Email tidak ditemukan dari akun ' . ucfirst($provider) . '.'
                ]);
            }

            // Daftar domain & email yang diizinkan
            $allowedDomains = [
                'telkom.id',
                'student.telkomuniversity.ac.id',
            ];

            $allowedEmails = [
                'valdaveisa751@gmail.com',
                'example@gmail.com',
            ];

            // Ambil domain dari email
            $domain = strtolower(substr(strrchr($email, '@'), 1));

            // Cek apakah diizinkan
            if (in_array($domain, $allowedDomains) || in_array($email, $allowedEmails)) {
                
                // Buat user baru jika belum ada
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $socialUser->getName() ?? explode('@', $email)[0],
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId()
                    ]
                );

                // Login dan redirect
                Auth::login($user, true);
                
                Log::info('User berhasil login via ' . $provider, [
                    'user_id' => $user->id,
                    'email' => $email
                ]);

                return redirect()->route('dashboard');
            }

            // Jika tidak termasuk yang diizinkan
            Log::warning('Email tidak diizinkan untuk login', [
                'email' => $email,
                'domain' => $domain,
                'provider' => $provider
            ]);

            return redirect()->route('login')->withErrors([
                'msg' => 'Email tidak diizinkan untuk login ke sistem.'
            ]);

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::error('Invalid state exception: ' . $e->getMessage(), [
                'provider' => $provider,
                'exception' => $e
            ]);

            return redirect()->route('login')->withErrors([
                'msg' => 'Sesi login tidak valid. Silakan coba lagi.'
            ]);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $body = $response ? json_decode((string) $response->getBody(), true) : null;
            
            Log::error('OAuth Client Exception: ' . $e->getMessage(), [
                'provider' => $provider,
                'status_code' => $e->getCode(),
                'response_body' => $body,
                'exception' => $e
            ]);

            return redirect()->route('login')->withErrors([
                'msg' => 'Terjadi kesalahan saat mengautentikasi dengan ' . ucfirst($provider) . '.'
            ]);

        } catch (\Exception $e) {
            // Log error lengkap untuk debugging
            Log::error('OAuth Callback Exception: ' . $e->getMessage(), [
                'provider' => $provider,
                'exception_class' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')->withErrors([
                'msg' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi administrator.'
            ]);
        }
    }
}