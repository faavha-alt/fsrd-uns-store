<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| generate-install-sql.php — bikin database/install.sql TANPA MySQL
|--------------------------------------------------------------------------
| Dipakai untuk paket instalasi manual (hosting tanpa SSH & tanpa wizard):
| user cukup import install.sql lewat phpMyAdmin.
|
| Cara kerjanya: semua file migration dijalankan (`up()`) dengan koneksi
| MySQL "palsu" yang tidak mengeksekusi apa pun — hanya merekam SQL yang
| dikompilasi oleh `Illuminate\Database\Schema\Grammars\MySqlGrammar`
| (jalur kode yang sama persis dengan `php artisan migrate`). Hasilnya
| adalah DDL MySQL yang identik dengan yang akan dijalankan migrasi asli.
|
| Usage:
|   php scripts/generate-install-sql.php <out.sql> [admin-email] [admin-password] [admin-name]
*/

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Database\MySqlConnection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

$repoDir = dirname(__DIR__);

require $repoDir.'/vendor/autoload.php';

$outPath      = $argv[1] ?? $repoDir.'/database/install.sql';
$adminEmail   = $argv[2] ?? 'admin@example.com';
$adminPass    = $argv[3] ?? bin2hex(random_bytes(6));
$adminName    = $argv[4] ?? 'Administrator';

// Boot aplikasi tanpa .env (file itu sengaja tidak ada di repo).
putenv('APP_ENV=production');
putenv('APP_KEY=base64:'.base64_encode(random_bytes(32)));
putenv('DB_CONNECTION=sqlite');
putenv('SESSION_DRIVER=array');
putenv('CACHE_STORE=array');
putenv('QUEUE_CONNECTION=sync');

/** @var \Illuminate\Foundation\Application $app */
$app = require $repoDir.'/bootstrap/app.php';
$app->make(ConsoleKernel::class)->bootstrap();

/**
 * Koneksi MySQL yang hanya merekam statement, tidak mengeksekusi.
 */
final class CapturingConnection extends MySqlConnection
{
    /** @var list<string> */
    public array $statements = [];

    public function statement($query, $bindings = [])
    {
        $this->statements[] = $query;

        return true;
    }

    public function affectingStatement($query, $bindings = [])
    {
        $this->statements[] = $query;

        return 0;
    }

    public function select($query, $bindings = [], $useReadPdo = true, array $fetchUsing = [])
    {
        return [];
    }

    public function selectOne($query, $bindings = [], $useReadPdo = true)
    {
        return null;
    }

    public function scalar($query, $bindings = [], $useReadPdo = true)
    {
        return 0;
    }

    public function getServerVersion(): string
    {
        return '8.0.35';
    }

    public function isMaria()
    {
        return false;
    }

    public function getPdo()
    {
        return null;
    }

    public function getReadPdo()
    {
        return null;
    }
}

$db = $app->make('db');

config([
    'database.connections.capture' => [
        'driver'      => 'mysql',
        'host'        => '127.0.0.1',
        'port'        => '3306',
        'database'    => 'fsrd_uns_store',
        'username'    => 'root',
        'password'    => '',
        'charset'     => 'utf8mb4',
        'collation'   => 'utf8mb4_unicode_ci',
        'prefix'      => '',
        'strict'      => true,
        'engine'      => 'InnoDB',
    ],
    'database.default' => 'capture',
]);

$connection = null;

$db->extend('capture', function ($config, $name) use (&$connection) {
    return $connection = new CapturingConnection(null, $config['database'], $config['prefix'], $config);
});

$db->setDefaultConnection('capture');

// Pastikan facade Schema benar-benar memakai koneksi capture.
Schema::connection('capture');

$migrationFiles = glob($repoDir.'/database/migrations/*.php') ?: [];
sort($migrationFiles);

$ran = [];

foreach ($migrationFiles as $file) {
    $migration = require $file;

    if (!is_object($migration) || !method_exists($migration, 'up')) {
        fwrite(STDERR, 'SKIP (bukan objek migration): '.basename($file)."\n");
        continue;
    }

    $before = count($connection->statements);

    try {
        $migration->up();
    } catch (Throwable $e) {
        fwrite(STDERR, 'GAGAL di '.basename($file).': '.$e->getMessage()."\n");
        foreach (array_slice(explode("\n", $e->getTraceAsString()), 0, 8) as $frame) {
            fwrite(STDERR, '    '.$frame."\n");
        }
        exit(1);
    }

    $added = count($connection->statements) - $before;
    $ran[] = pathinfo($file, PATHINFO_FILENAME);

    printf("%-70s %d statement\n", basename($file), $added);
}

$ddl = $connection->statements;
$now = date('Y-m-d H:i:s');
$adminHash = Hash::make($adminPass);

$body = [];
foreach ($ddl as $statement) {
    $body[] = rtrim($statement, ';').';';
}

$migrationRows = [];
foreach ($ran as $name) {
    $migrationRows[] = "('".$name."', 1)";
}

$sql = [];
$sql[] = '-- Database schema untuk fsrd-uns-store (dibuat '.$now.' oleh scripts/generate-install-sql.php).';
$sql[] = '-- Import file ini lewat phpMyAdmin SEBELUM mengedit .env / membuka situs.';
$sql[] = '';
$sql[] = 'SET NAMES utf8mb4;';
$sql[] = 'SET time_zone = "+07:00";';
$sql[] = '';
$sql[] = 'CREATE TABLE `migrations` (';
$sql[] = '  `id` int unsigned NOT NULL AUTO_INCREMENT,';
$sql[] = '  `migration` varchar(255) NOT NULL,';
$sql[] = '  `batch` int NOT NULL,';
$sql[] = '  PRIMARY KEY (`id`)';
$sql[] = ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
$sql[] = '';

// DDL dari migration, urut sesuai nama file migration.
$sql[] = '-- ---------------------------------------------------------------------';
$sql[] = '-- Tabel-tabel aplikasi (hasil kompilasi migration, urut sesuai file)';
$sql[] = '-- ---------------------------------------------------------------------';
$sql = array_merge($sql, $body);
$sql[] = '';

// Catatan migrasi + akun admin + setting awal (SETELAH semua tabel ada).
$sql[] = '-- ---------------------------------------------------------------------';
$sql[] = '-- Catatan migrasi yang sudah dijalankan';
$sql[] = '-- ---------------------------------------------------------------------';
$sql[] = 'INSERT INTO `migrations` (`migration`, `batch`) VALUES'."\n  ".implode(",\n  ", $migrationRows).';';
$sql[] = '';
$sql[] = '-- ---------------------------------------------------------------------';
$sql[] = '-- Akun admin pertama. GANTI PASSWORD setelah login pertama (menu User).';
$sql[] = '-- ---------------------------------------------------------------------';
$sql[] = 'INSERT INTO `users` (`name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `created_at`, `updated_at`) VALUES';
$sql[] = "  ('".addslashes($adminName)."', '".addslashes($adminEmail)."', '".$now."', '".addslashes($adminHash)."', 'admin', 1, '".$now."', '".$now."');";
$sql[] = '';
$sql[] = 'INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES';
$sql[] = "  ('site_name', 'FSRD UNS Store', '".$now."', '".$now."'),";
$sql[] = "  ('contact_email', '".addslashes($adminEmail)."', '".$now."', '".$now."');";
$sql[] = '';

$output = implode("\n", $sql)."\n";

if (file_put_contents($outPath, $output) === false) {
    fwrite(STDERR, 'Tidak bisa menulis '.$outPath."\n");
    exit(1);
}

fwrite(STDERR, "\nOK: ".count($ddl).' statement DDL -> '.$outPath."\n");
fwrite(STDERR, 'Admin: '.$adminEmail.' / '.$adminPass."\n");
