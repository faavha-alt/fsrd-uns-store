<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Server baru belum punya .env (file ini sengaja tidak ikut di-git).
// Supaya wizard /install bisa tampil (framework butuh APP_KEY untuk enkripsi
// cookie session), salin dari .env.example dan isi APP_KEY acak dulu.
// Setelah wizard selesai, .env ini akan ditimpa ulang dengan konfigurasi asli.
if (!file_exists($envPath = __DIR__.'/../.env') && file_exists($envExample = __DIR__.'/../.env.example') && is_writable(dirname($envPath))) {
    $env = file_get_contents($envExample);
    $randomKey = 'base64:'.base64_encode(random_bytes(32));
    $env = preg_replace('/^APP_KEY=.*$/m', 'APP_KEY='.$randomKey, $env, 1);
    @file_put_contents($envPath, $env);
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
