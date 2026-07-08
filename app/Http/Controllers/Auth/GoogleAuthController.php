<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    

private function configureGoogle(): void
{
    $clientId     = \App\Models\Setting::get('google_client_id');
    $clientSecret = \App\Models\Setting::get('google_client_secret');
    $redirectUri  = \App\Models\Setting::get('google_redirect_uri');

    if ($clientId && $clientSecret && $redirectUri) {
        config([
            'services.google.client_id'     => $clientId,
            'services.google.client_secret' => $clientSecret,
            'services.google.redirect'      => $redirectUri,
        ]);
    }
}
// Redirect ke Google
    public function redirect()
{
    // Cek apakah Google OAuth aktif
    if (!\App\Models\Setting::get('google_oauth_enabled', '1')) {
        return redirect()->route('buyer.login')
            ->with('error', 'Login Google tidak tersedia saat ini.');
    }

    $this->configureGoogle();
    return Socialite::driver('google')->redirect();
}
    // Callback dari Google
    public function callback()
{
    $this->configureGoogle();

    try {
        $googleUser = Socialite::driver('google')->user();
    } catch (\Exception $e) {
        return redirect()->route('buyer.login')
            ->with('error', 'Login Google gagal. Silakan coba lagi.');
    }

        // Cari user by google_id atau email
        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            // Cek apakah email sudah terdaftar
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Email sudah ada — update google_id
                if ($user->role !== UserRole::Buyer) {
                    return redirect()->route('buyer.login')
                        ->with('error', 'Email ini terdaftar sebagai admin/kurator. Gunakan login biasa.');
                }
                $user->update(['google_id' => $googleUser->getId()]);
            } else {
                // Buat akun baru
                $user = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password'  => bcrypt(Str::random(32)),
                    'role'      => UserRole::Buyer,
                    'is_active' => true,
                ]);
            }
        }

        // Cek akun aktif
        if (!$user->is_active) {
            return redirect()->route('buyer.login')
                ->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('success', 'Selamat datang, ' . $user->name . '!');
    }
}
