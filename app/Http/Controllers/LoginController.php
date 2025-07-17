<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    private const SUPPORTED_PROVIDERS = ['google', 'microsoft'];

    private const ALLOWED_DOMAINS = [
        'telkom.co.id',
        'student.telkomuniversity.ac.id',
    ];

    private const ALLOWED_EMAILS = [
        'valdaveisa751@gmail.com',
        'aryaid612@gmail.com',
        'example@gmail.com',
    ];

    public function redirect($provider)
    {
        try {
            if (!$this->isProviderSupported($provider)) {
                return $this->redirectToLoginWithError('Provider login tidak didukung.');
            }

            if ($provider === 'microsoft') {
                return Socialite::driver($provider)
                    ->scopes(['openid', 'profile', 'email', 'User.Read']) // tambahan scope agar email bisa muncul
                    ->redirect();
            }

            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            Log::error('Error pada redirect OAuth', [
                'provider' => $provider,
                'message' => $e->getMessage(),
                'exception' => $e
            ]);

            return $this->redirectToLoginWithError('Terjadi kesalahan saat mengarahkan ke ' . ucfirst($provider));
        }
    }

    public function callback($provider)
    {
        try {
            if (!$this->isProviderSupported($provider)) {
                return $this->redirectToLoginWithError('Provider login tidak didukung.');
            }

            $socialUser = $this->getSocialUser($provider);
            if (!$socialUser) {
                return $this->redirectToLoginWithError('Gagal mengambil data dari ' . ucfirst($provider) . ',  Silahkan coba lagi');
            }

            $email = $this->validateEmail($socialUser, $provider);
            if (!$email) {
                return $this->redirectToLoginWithError('Email tidak ditemukan dari akun ' . ucfirst($provider));
            }

            if (!$this->isEmailAllowed($email)) {
                Log::warning('Email tidak diizinkan', [
                    'email' => $email,
                    'provider' => $provider,
                ]);

                return $this->redirectToLoginWithError('Email tidak diizinkan untuk login ke sistem.');
            }

            $user = $this->createOrUpdateUser($socialUser, $email, $provider);
            Auth::login($user, true);

            Log::info('User berhasil login', [
                'user_id' => $user->id,
                'email' => $email,
            ]);

            return redirect()->route('dashboard');
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::error('Invalid state exception', [
                'provider' => $provider,
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);

            return $this->redirectToLoginWithError('Sesi login tidak valid. Silakan coba lagi.');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->logClientException($e, $provider);
            return $this->redirectToLoginWithError('Gagal autentikasi dengan ' . ucfirst($provider));
        } catch (\Exception $e) {
            $this->logGeneralException($e, $provider);
            return $this->redirectToLoginWithError('Kesalahan sistem. Silakan hubungi administrator.');
        }
    }

    private function isProviderSupported($provider): bool
    {
        return in_array($provider, self::SUPPORTED_PROVIDERS);
    }

    private function getSocialUser($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            if (!$socialUser) {
                Log::error('Social user kosong', ['provider' => $provider]);
                return null;
            }

            Log::info('Social user data', [
                'provider' => $provider,
                'id' => $socialUser->getId(),
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'avatar' => $socialUser->getAvatar(),
                'raw' => $socialUser->user, // full raw data dari Microsoft/Google
            ]);

            return $socialUser;
        } catch (\Exception $e) {
            Log::error('Error mengambil social user', [
                'provider' => $provider,
                'message' => $e->getMessage(),
                'exception' => $e
            ]);
            return null;
        }
    }

    private function validateEmail($socialUser, $provider): ?string
    {
        $email = $socialUser->getEmail()
            ?? $socialUser->user['mail'] // Microsoft kadang pakai ini
            ?? $socialUser->user['userPrincipalName'] // fallback lainnya
            ?? null;

        if (!$email) {
            Log::warning('Email tidak ditemukan', [
                'provider' => $provider,
                'social_id' => $socialUser->getId(),
                'raw' => $socialUser->user ?? [],
            ]);
            return null;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::warning('Format email tidak valid', [
                'email' => $email,
                'provider' => $provider,
            ]);
            return null;
        }

        return strtolower($email);
    }

    private function isEmailAllowed($email): bool
    {
        $allowedExactDomains = ['telkom.co.id', 'student.telkomuniversity.ac.id'];
        $allowedEmails = self::ALLOWED_EMAILS;

        $domain = strtolower(substr(strrchr($email, "@"), 1));

        // Cek apakah domain persis sesuai yang diizinkan
        if (in_array($domain, $allowedExactDomains)) {
            return true;
        }

        // Jika bukan domain yang diizinkan, cek apakah email spesifiknya diizinkan
        if (in_array($email, $allowedEmails)) {
            return true;
        }

        return false;
    }

    private function getDomainFromEmail($email): string
    {
        return strtolower(substr(strrchr($email, '@'), 1));
    }

    private function createOrUpdateUser($socialUser, $email, $provider): User
    {
        $userData = [
            'name' => $socialUser->getName() ?? $this->generateNameFromEmail($email),
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'avatar' => $socialUser->getAvatar(),
        ];

        $user = User::firstOrNew(['email' => $email]);
        $user->fill($userData);
        $user->save();

        return $user;
    }

    private function generateNameFromEmail($email): string
    {
        $name = explode('@', $email)[0];
        return ucwords(str_replace(['.', '_', '-'], ' ', $name));
    }

    private function redirectToLoginWithError($message)
    {
        return redirect()->route('login')->withErrors(['msg' => $message]);
    }

    private function logClientException($e, $provider): void
    {
        $response = $e->getResponse();
        $body = $response ? json_decode((string) $response->getBody(), true) : null;

        Log::error('OAuth Client Exception', [
            'provider' => $provider,
            'status_code' => $e->getCode(),
            'response_body' => $body,
            'exception' => $e
        ]);
    }

    private function logGeneralException($e, $provider): void
    {
        Log::error('OAuth Callback Exception', [
            'provider' => $provider,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
