<?php

namespace App\Console\Commands;

use App\Helpers\LicenseHelper;
use App\Models\Setting;
use Illuminate\Console\Command;

class ActivateLicense extends Command
{
    protected $signature = 'license:activate {key? : License key (kosongkan untuk pakai LICENSE_KEY di .env)}';
    protected $description = 'Aktivasi manual license key untuk domain saat ini (dipakai untuk retrofit instalasi lama)';

    public function handle()
    {
        $key = $this->argument('key') ?: config('license.key');

        if (!$key) {
            $this->error('License key tidak ada — isi argumen atau set LICENSE_KEY di .env dulu.');
            return self::FAILURE;
        }

        if (!config('license.server_url')) {
            $this->error('LICENSE_SERVER_URL belum diisi di .env.');
            return self::FAILURE;
        }

        $domain = LicenseHelper::currentDomain();
        $this->info("Mengaktivasi key untuk domain: {$domain}");

        $result = LicenseHelper::activate($key, $domain);
        $status = $result['status'] ?? 'error';

        if ($status === 'ok') {
            Setting::set('license_status', 'valid');
            Setting::set('license_last_check_at', now()->toDateTimeString());
            $this->info('Aktivasi berhasil.');
            return self::SUCCESS;
        }

        $this->error('Aktivasi gagal: '.($result['message'] ?? $status));
        return self::FAILURE;
    }
}
