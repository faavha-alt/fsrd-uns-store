<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| index.php untuk hosting yang document root-nya TERKUNCI di public_html/
|--------------------------------------------------------------------------
| Dipakai oleh paket hasil `scripts/build-shared-hosting.sh`.
|
| Layout server (contoh cPanel):
|   /home/USER/fsrd-uns-store/   <- folder aplikasi (app/, vendor/, storage/, ...)
|   /home/USER/public_html/      <- document root (file ini + css/ + .htaccess)
|
| Nama folder aplikasi bebas. Default yang dicari: "fsrd-uns-store"; kalau tidak
| ketemu, index ini otomatis memindai folder bersaudara public_html yang punya
| bootstrap/app.php + vendor/autoload.php.
*/

$appBase = __DIR__.'/../fsrd-uns-store';

if (!is_file($appBase.'/vendor/autoload.php')) {
    foreach ((glob(dirname(__DIR__).'/*/bootstrap/app.php') ?: []) as $found) {
        $candidate = dirname(dirname($found));

        if (is_file($candidate.'/vendor/autoload.php')) {
            $appBase = $candidate;
            break;
        }
    }
}

if (!is_file($appBase.'/vendor/autoload.php')) {
    http_response_code(500);
    exit(
        'Folder aplikasi Laravel tidak ditemukan. Pastikan folder "fsrd-uns-store" '
        .'(berisi app/, vendor/, bootstrap/) di-upload SATU LEVEL DI ATAS public_html, '
        .'lalu buka lagi halaman ini.'
    );
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $appBase.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Server baru belum punya .env (file ini sengaja tidak ikut di-git).
// Supaya wizard /install bisa tampil (framework butuh APP_KEY untuk enkripsi
// cookie session), salin dari .env.example dan isi APP_KEY acak dulu.
// Setelah wizard selesai, .env ini akan ditimpa ulang dengan konfigurasi asli.
if (!file_exists($envPath = $appBase.'/.env') && file_exists($envExample = $appBase.'/.env.example') && is_writable($appBase)) {
    $env = file_get_contents($envExample);
    $randomKey = 'base64:'.base64_encode(random_bytes(32));
    $env = preg_replace('/^APP_KEY=.*$/m', 'APP_KEY='.$randomKey, $env, 1);
    @file_put_contents($envPath, $env);
}

// Gambar/upload disimpan di <app>/storage/app/public dan diakses lewat
// https://domain/storage/... — di hosting tanpa SSH, `php artisan storage:link`
// tidak bisa dijalankan manual. Symlink-nya dibuat otomatis di sini (sekali,
// idempotent, tidak fatal kalau hosting melarang symlink()).
$storageLink = __DIR__.'/storage';
if (!file_exists($storageLink) && is_writable(__DIR__) && is_dir($appBase.'/storage/app/public')) {
    if (is_link($storageLink)) {
        @unlink($storageLink);
    }
    @symlink($appBase.'/storage/app/public', $storageLink);
}

// Register the Composer autoloader...
require $appBase.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $appBase.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
