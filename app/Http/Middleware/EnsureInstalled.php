<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureInstalled
{
    public function handle(Request $request, Closure $next)
    {
        $installed = file_exists(storage_path('installed.lock'));

        if (!$installed && !$request->is('install*', 'up')) {
            return redirect()->route('install.welcome');
        }

        if ($installed && $request->is('install*')) {
            return redirect()->route('home')->with('info', 'Aplikasi sudah terinstall.');
        }

        return $next($request);
    }
}
