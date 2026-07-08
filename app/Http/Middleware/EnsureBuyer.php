<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureBuyer
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('buyer.login')
                ->with('info', 'Silakan login terlebih dahulu.');
        }

        if (Auth::user()->role !== UserRole::Buyer) {
            abort(403);
        }

        return $next($request);
    }
}