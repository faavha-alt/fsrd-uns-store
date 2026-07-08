<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) return $next($request);

        $user = Auth::user();
        if ($user->role->value === 'buyer') return $next($request);

        $timeout = 120 * 60; // 2 jam dalam detik
        $lastActivity = session('admin_last_activity');

        if ($lastActivity && (time() - $lastActivity > $timeout)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('info', 'Sesi Anda telah berakhir karena tidak aktif. Silakan login kembali.');
        }

        session(['admin_last_activity' => time()]);
        return $next($request);
    }
}
