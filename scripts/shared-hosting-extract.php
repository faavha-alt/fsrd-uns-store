<?php

/*
|--------------------------------------------------------------------------
| Helper sekali-pakai: extract paket rilis di hosting TANPA SSH / File Manager
|--------------------------------------------------------------------------
| Untuk hosting yang cuma kasih akses FTP: upload file zip paket rilis
| (fsrd-uns-store-*-cpanel.zip) dan file ini ke dalam public_html/, lalu buka
| https://domain-anda/_extract.php di browser. Script ini memecah isi zip ke
| tempat yang benar:
|   fsrd-uns-store/...  ->  satu level DI ATAS public_html
|   public_html/...     ->  di dalam public_html ini
| Setelah selesai, HAPUS file ini (dan file zip-nya) dari server.
*/

set_time_limit(0);
header('Content-Type: text/plain; charset=utf-8');

$zipPath = null;
foreach (array_merge(glob(__DIR__.'/fsrd-uns-store*cpanel*.zip') ?: [], glob(__DIR__.'/*.zip') ?: []) as $candidate) {
    $zipPath = $candidate;
    break;
}

if ($zipPath === null) {
    exit("GAGAL: tidak ada file .zip di folder ini. Upload fsrd-uns-store-*-cpanel.zip ke public_html/ dulu.\n");
}

$home = dirname(__DIR__);
$zip = new ZipArchive();

if ($zip->open($zipPath) !== true) {
    exit("GAGAL: file zip tidak bisa dibuka: {$zipPath}\n");
}

echo "Extract: ".basename($zipPath)."\n\n";

$appRoot = null;
$files = 0;
$skipped = 0;

for ($i = 0; $i < $zip->numFiles; $i++) {
    $name = $zip->getNameIndex($i);

    if ($name === false || $name === '' || str_starts_with($name, '__MACOSX/')) {
        continue;
    }

    if (str_starts_with($name, 'public_html/')) {
        $dest = __DIR__.'/'.substr($name, strlen('public_html/'));
    } elseif (str_starts_with($name, 'fsrd-uns-store/')) {
        $dest = $home.'/'.$name;
        $appRoot ??= $home.'/fsrd-uns-store';
    } elseif (!str_contains(rtrim($name, '/'), '/')) {
        $dest = $home.'/'.$name;
    } else {
        $skipped++;
        continue;
    }

    if (str_ends_with($dest, '/')) {
        if (!is_dir($dest)) {
            @mkdir($dest, 0755, true);
        }
        continue;
    }

    if (!is_dir($dir = dirname($dest))) {
        @mkdir($dir, 0755, true);
    }

    $in = $zip->getStream($name);
    if ($in === false) {
        $skipped++;
        continue;
    }

    $out = @fopen($dest, 'wb');
    if ($out === false) {
        fclose($in);
        $skipped++;
        continue;
    }

    stream_copy_to_stream($in, $out);
    fclose($in);
    fclose($out);
    @chmod($dest, 0644);
    $files++;
}

$zip->close();

// Folder yang WAJIB bisa ditulis PHP (session, cache, log, upload gambar).
if ($appRoot !== null) {
    foreach ([
        $appRoot.'/storage',
        $appRoot.'/storage/app',
        $appRoot.'/storage/app/public',
        $appRoot.'/storage/framework',
        $appRoot.'/storage/framework/cache',
        $appRoot.'/storage/framework/sessions',
        $appRoot.'/storage/framework/views',
        $appRoot.'/storage/logs',
        $appRoot.'/bootstrap/cache',
    ] as $dir) {
        if (is_dir($dir)) {
            @chmod($dir, 0775);
        }
    }
}

echo "Selesai: {$files} file diextract".($skipped > 0 ? ", {$skipped} dilewati" : '').".\n\n";
echo "Langkah berikutnya:\n";
echo "1. HAPUS file ini (".basename(__FILE__).") dan file zip-nya dari public_html/.\n";
echo "2. Buka https://domain-anda/ dan ikuti wizard /install.\n";

if ($appRoot === null) {
    echo "\nCATATAN: entri fsrd-uns-store/ tidak ditemukan di zip ini.\n";
}
