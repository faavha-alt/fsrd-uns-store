<?php

namespace App\Http\Middleware;

use App\Helpers\LicenseHelper;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EnsureLicensed
{
    public function handle(Request $request, Closure $next)
    {
        // Belum lewat wizard install — biarkan EnsureInstalled yang menangani.
        if (!file_exists(storage_path('installed.lock'))) {
            return $next($request);
        }

        // Fitur lisensi belum dikonfigurasi (mis. deploy lama sebelum fitur ini ada) — jangan blokir.
        if (!config('license.server_url') || !config('license.key')) {
            return $next($request);
        }

        $status = Setting::get('license_status');

        if (in_array($status, ['revoked', 'mismatched'], true)) {
            return $this->locked($status);
        }

        $lastOk = Setting::get('license_last_check_at');
        $stale = !$lastOk || now()->diffInMinutes($lastOk) > 60;

        // Refresh berkala tanpa membebani tiap request — throttle & cegah tabrakan antar request bersamaan.
        if ($stale && Cache::add('license_refresh_lock', 1, now()->addMinutes(5))) {
            $this->refresh();
            $status = Setting::get('license_status');
            $lastOk = Setting::get('license_last_check_at');

            if (in_array($status, ['revoked', 'mismatched'], true)) {
                return $this->locked($status);
            }
        }

        $graceDays = (int) config('license.grace_days', 3);

        if (!$lastOk || now()->diffInDays($lastOk) > $graceDays) {
            return $this->locked('unreachable');
        }

        return $next($request);
    }

    protected function refresh(): void
    {
        $result = LicenseHelper::check(config('license.key'), LicenseHelper::currentDomain());
        $status = $result['status'] ?? null;

        if ($status === 'ok') {
            Setting::set('license_status', 'valid');
            Setting::set('license_last_check_at', now()->toDateTimeString());
        } elseif ($status === 'revoked' || $status === 'invalid') {
            Setting::set('license_status', 'revoked');
        } elseif ($status === 'mismatched') {
            Setting::set('license_status', 'mismatched');
        }
        // status 'unreachable'/'error' → jangan ubah apa pun, biarkan masa tenggang (grace_days) yang menentukan.
    }

    protected function locked(string $reason)
    {
        return response()->view('license-locked', ['reason' => $reason], 503);
    }
}
