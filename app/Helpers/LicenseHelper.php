<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Throwable;

class LicenseHelper
{
    public static function activate(string $key, string $domain): array
    {
        return static::call('activate.php', $key, $domain);
    }

    public static function check(string $key, string $domain): array
    {
        return static::call('check.php', $key, $domain);
    }

    public static function currentDomain(): string
    {
        $host = parse_url((string) config('app.url'), PHP_URL_HOST);

        return $host ?: request()->getHost();
    }

    protected static function call(string $endpoint, string $key, string $domain): array
    {
        $base = rtrim((string) config('license.server_url'), '/');

        if ($base === '' || $key === '') {
            return ['status' => 'error', 'message' => 'License key atau LICENSE_SERVER_URL belum dikonfigurasi.'];
        }

        try {
            $response = Http::connectTimeout(5)
                ->timeout(10)
                ->withHeaders(['X-License-Secret' => (string) config('license.api_secret')])
                ->asForm()
                ->post("{$base}/{$endpoint}", [
                    'license_key' => $key,
                    'domain'      => $domain,
                    'app'         => 'fsrd-uns-store',
                ]);

            return $response->json() ?? ['status' => 'error', 'message' => 'Respons license server tidak valid.'];
        } catch (Throwable $e) {
            \Log::warning('LicenseHelper: gagal menghubungi license server', [
                'endpoint' => $endpoint,
                'base'     => $base,
                'error'    => $e->getMessage(),
            ]);

            return ['status' => 'unreachable', 'message' => $e->getMessage()];
        }
    }
}
