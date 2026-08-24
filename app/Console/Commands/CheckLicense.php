<?php

namespace App\Console\Commands;

use App\Helpers\LicenseHelper;
use App\Models\Setting;
use Illuminate\Console\Command;

class CheckLicense extends Command
{
    protected $signature = 'license:check';
    protected $description = 'Verifikasi status license key ke license server (dijalankan berkala via cron)';

    public function handle()
    {
        if (!config('license.server_url') || !config('license.key')) {
            $this->warn('LICENSE_KEY / LICENSE_SERVER_URL belum dikonfigurasi di .env — dilewati.');
            return self::SUCCESS;
        }

        $result = LicenseHelper::check(config('license.key'), LicenseHelper::currentDomain());
        $status = $result['status'] ?? 'error';

        switch ($status) {
            case 'ok':
                Setting::set('license_status', 'valid');
                Setting::set('license_last_check_at', now()->toDateTimeString());
                $this->info('Lisensi valid.');
                break;
            case 'revoked':
            case 'invalid':
                Setting::set('license_status', 'revoked');
                $this->error('Lisensi ditolak/di-revoke oleh license server.');
                break;
            case 'mismatched':
                Setting::set('license_status', 'mismatched');
                $this->error('Domain saat ini tidak cocok dengan lisensi yang terdaftar.');
                break;
            default:
                $this->warn('License server tidak bisa dihubungi ('.($result['message'] ?? $status).'). Masa tenggang akan berlaku.');
        }

        return self::SUCCESS;
    }
}
