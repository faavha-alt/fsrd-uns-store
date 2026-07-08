<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role !== UserRole::Buyer) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Cek rate limit
        $key = 'login.' . Str::lower($request->email) . '.' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ])->onlyInput('email');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Pastikan bukan buyer
            if ($user->role === UserRole::Buyer) {
                Auth::logout();
                RateLimiter::hit($key, 900); // 15 menit
                return back()->withErrors([
                    'email' => 'Akun ini tidak memiliki akses admin.',
                ])->onlyInput('email');
            }

            // Pastikan akun aktif
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
                ])->onlyInput('email');
            }

            RateLimiter::clear($key);
            $request->session()->regenerate();

            // Log aktivitas login
            \Log::channel('admin_activity')->info('Admin login', [
                'user_id' => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'ip'      => $request->ip(),
                'time'    => now()->toDateTimeString(),
            ]);

            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($key, 900); // Lock 15 menit setelah 5x gagal
        $attempts = RateLimiter::attempts($key);
        $remaining = 5 - $attempts;

        return back()->withErrors([
            'email' => $remaining > 0
                ? "Email atau password salah. Sisa percobaan: {$remaining}x"
                : 'Akun dikunci sementara. Coba lagi dalam 15 menit.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        \Log::channel('admin_activity')->info('Admin logout', [
            'user_id' => Auth::id(),
            'email'   => Auth::user()->email ?? '-',
            'ip'      => $request->ip(),
            'time'    => now()->toDateTimeString(),
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}