<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Helpers\EnvHelper;
use App\Helpers\LicenseHelper;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDO;
use PDOException;
use Throwable;

class InstallController extends Controller
{
    protected const MIN_PHP = '8.3.0';

    protected const REQUIRED_EXTENSIONS = [
        'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'fileinfo', 'gd', 'curl',
    ];

    public function license()
    {
        if (!$this->licenseServerConfigured()) {
            // Paket instalasi ini tidak dibekali konfigurasi license server —
            // JANGAN diam-diam dilewati, hentikan instalasi sama sekali.
            return view('install.license', ['unconfigured' => true]);
        }

        return view('install.license', [
            'unconfigured' => false,
            'old' => session('install.license_key', ''),
        ]);
    }

    public function licenseStore(Request $request)
    {
        if (!$this->licenseServerConfigured()) {
            return redirect()->route('install.license');
        }

        $data = $request->validate([
            'license_key' => 'required|string|max:255',
        ]);

        $domain = LicenseHelper::currentDomain();
        $result = LicenseHelper::activate($data['license_key'], $domain);
        $status = $result['status'] ?? 'error';

        if ($status !== 'ok') {
            $message = match ($status) {
                'invalid'     => 'License key tidak ditemukan.',
                'revoked'     => 'License key ini sudah dinonaktifkan.',
                'mismatched'  => 'License key ini sudah dipakai di domain lain. Hubungi penyedia layanan untuk memindahkannya ke domain ini.',
                'unreachable' => 'Tidak bisa menghubungi license server. Cek koneksi internet server ini, lalu coba lagi. Detail teknis: '.($result['message'] ?? '-'),
                default       => 'Aktivasi gagal: '.($result['message'] ?? $status),
            };

            return back()->withErrors(['license_key' => $message])->withInput();
        }

        session([
            'install.license'     => 'ok',
            'install.license_key' => $data['license_key'],
        ]);

        return redirect()->route('install.database');
    }

    public function welcome()
    {
        $checks = $this->runRequirementChecks();
        $ready = collect($checks)->every(fn ($check) => $check['ok']);

        return view('install.welcome', compact('checks', 'ready'));
    }

    public function database()
    {
        if (!session('install.license')) {
            return redirect()->route('install.license');
        }

        return view('install.database', [
            'old' => session('install.db', []),
        ]);
    }

    public function databaseStore(Request $request)
    {
        if (!session('install.license')) {
            return redirect()->route('install.license');
        }

        $data = $request->validate([
            'db_host'     => 'required|string|max:255',
            'db_port'     => 'required|integer|min:1|max:65535',
            'db_database' => 'required|string|max:64',
            'db_username' => 'required|string|max:255',
            'db_password' => 'nullable|string|max:255',
        ]);

        $dbName = str_replace('`', '', $data['db_database']);
        $options = [PDO::ATTR_TIMEOUT => 5];

        try {
            // Coba langsung konek ke database yang dituju — cocok untuk hosting
            // yang database-nya sudah disediakan lewat cPanel (user tidak punya izin CREATE DATABASE).
            new PDO("mysql:host={$data['db_host']};port={$data['db_port']};dbname={$dbName};charset=utf8mb4", $data['db_username'], $data['db_password'] ?? '', $options);
        } catch (PDOException $e) {
            // Database belum ada — coba buatkan (butuh izin CREATE DATABASE).
            try {
                $pdo = new PDO("mysql:host={$data['db_host']};port={$data['db_port']};charset=utf8mb4", $data['db_username'], $data['db_password'] ?? '', $options);
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            } catch (PDOException $e2) {
                return back()->withErrors(['db_host' => 'Koneksi database gagal: '.$e2->getMessage()])->withInput();
            }
        }

        session(['install.db' => $data]);

        return redirect()->route('install.account');
    }

    public function account()
    {
        if (!session('install.db')) {
            return redirect()->route('install.database');
        }

        return view('install.account', [
            'old' => session('install.account', []),
        ]);
    }

    public function accountStore(Request $request)
    {
        if (!session('install.db')) {
            return redirect()->route('install.database');
        }

        $data = $request->validate([
            'site_name'      => 'required|string|max:255',
            'admin_name'     => 'required|string|max:255',
            'admin_email'    => 'required|email|max:255',
            'admin_password' => 'required|string|min:8|confirmed',
        ]);

        session(['install.account' => [
            'site_name'      => $data['site_name'],
            'admin_name'     => $data['admin_name'],
            'admin_email'    => $data['admin_email'],
            'admin_password' => $data['admin_password'],
        ]]);

        return redirect()->route('install.confirm');
    }

    public function confirm()
    {
        if (!session('install.license') || !session('install.db') || !session('install.account')) {
            return redirect()->route('install.database');
        }

        return view('install.confirm', [
            'db'      => session('install.db'),
            'account' => session('install.account'),
        ]);
    }

    public function run(Request $request)
    {
        if (file_exists(storage_path('installed.lock'))) {
            return redirect()->route('home');
        }

        $db = session('install.db');
        $account = session('install.account');

        if (!$db || !$account) {
            return redirect()->route('install.database');
        }

        try {
            $envValues = [
                'APP_NAME'       => $account['site_name'],
                'APP_ENV'        => 'production',
                'APP_DEBUG'      => 'false',
                'APP_URL'        => $request->getSchemeAndHttpHost(),
                'DB_CONNECTION'  => 'mysql',
                'DB_HOST'        => $db['db_host'],
                'DB_PORT'        => $db['db_port'],
                'DB_DATABASE'    => $db['db_database'],
                'DB_USERNAME'    => $db['db_username'],
                'DB_PASSWORD'    => $db['db_password'] ?? '',
                'SESSION_DRIVER' => 'database',
                'CACHE_STORE'    => 'database',
                'QUEUE_CONNECTION' => 'database',
            ];

            if (session('install.license_key')) {
                $envValues['LICENSE_KEY'] = session('install.license_key');
            }

            EnvHelper::set($envValues);

            Artisan::call('config:clear');

            config([
                'database.default' => 'mysql',
                'database.connections.mysql.host'     => $db['db_host'],
                'database.connections.mysql.port'     => $db['db_port'],
                'database.connections.mysql.database' => $db['db_database'],
                'database.connections.mysql.username' => $db['db_username'],
                'database.connections.mysql.password' => $db['db_password'] ?? '',
            ]);
            DB::purge('mysql');
            DB::reconnect('mysql');

            Artisan::call('key:generate', ['--force' => true]);
            Artisan::call('migrate', ['--force' => true]);

            try {
                Artisan::call('storage:link');
            } catch (Throwable $e) {
                // Non-fatal — beberapa hosting sudah symlink otomatis / tidak izinkan symlink.
            }

            User::create([
                'name'      => $account['admin_name'],
                'email'     => $account['admin_email'],
                'password'  => Hash::make($account['admin_password']),
                'role'      => UserRole::Admin,
                'is_active' => true,
            ]);

            Setting::set('site_name', $account['site_name']);
            Setting::set('contact_email', $account['admin_email']);

            if (session('install.license_key')) {
                Setting::set('license_status', 'valid');
                Setting::set('license_last_check_at', now()->toDateTimeString());
            }

            file_put_contents(storage_path('installed.lock'), 'Installed at '.now()->toDateTimeString());

            $request->session()->forget(['install.db', 'install.account', 'install.license', 'install.license_key']);

            return redirect()->route('install.success');
        } catch (Throwable $e) {
            return redirect()->route('install.confirm')->withErrors([
                'install' => 'Instalasi gagal: '.$e->getMessage(),
            ]);
        }
    }

    public function success()
    {
        if (!file_exists(storage_path('installed.lock'))) {
            return redirect()->route('install.welcome');
        }

        return view('install.success');
    }

    protected function licenseServerConfigured(): bool
    {
        return (bool) config('license.server_url') && (bool) config('license.api_secret');
    }

    protected function runRequirementChecks(): array
    {
        $checks = [];

        $checks['PHP >= '.self::MIN_PHP] = [
            'ok'    => version_compare(PHP_VERSION, self::MIN_PHP, '>='),
            'label' => 'Versi PHP terpasang: '.PHP_VERSION,
        ];

        foreach (self::REQUIRED_EXTENSIONS as $ext) {
            $checks["Ekstensi PHP: {$ext}"] = [
                'ok'    => extension_loaded($ext),
                'label' => extension_loaded($ext) ? 'Terpasang' : 'Tidak ditemukan',
            ];
        }

        $writableDirs = [
            'storage/'                     => storage_path(),
            'storage/framework/'           => storage_path('framework'),
            'storage/framework/sessions/'  => storage_path('framework/sessions'),
            'storage/framework/cache/'     => storage_path('framework/cache'),
            'storage/logs/'                => storage_path('logs'),
            'bootstrap/cache/'             => base_path('bootstrap/cache'),
            '.env (root proyek)'           => base_path(),
        ];

        foreach ($writableDirs as $label => $path) {
            $checks["Folder writable: {$label}"] = [
                'ok'    => is_writable($path),
                'label' => is_writable($path) ? 'Writable' : 'Tidak bisa ditulis — cek permission',
            ];
        }

        $checks['vendor/ (dependency Composer)'] = [
            'ok'    => is_dir(base_path('vendor')),
            'label' => is_dir(base_path('vendor')) ? 'Ditemukan' : 'Tidak ditemukan — upload folder vendor/ juga',
        ];

        return $checks;
    }
}
