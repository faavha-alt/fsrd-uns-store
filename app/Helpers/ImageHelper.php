<?php

namespace App\Helpers;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Resize & simpan gambar
     * @param UploadedFile $file
     * @param string $folder — folder di storage/app/public/
     * @param int $width
     * @param int $height
     * @return string — path relatif dari storage/app/public/
     */
    public static function upload(
        UploadedFile $file,
        string $folder,
        int $width = 800,
        int $height = 600
    ): string {
        $filename = Str::random(40) . '.jpg';
        $path = storage_path("app/public/{$folder}");

        // Buat folder kalau belum ada
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }

        // Resize & save
        Image::read($file)
            ->cover($width, $height)
            ->toJpeg(85)
            ->save("{$path}/{$filename}");

        return "{$folder}/{$filename}";
    }

    /**
     * Upload multiple images (untuk produk)
     */
    public static function uploadMultiple(
        array $files,
        string $folder,
        int $width = 800,
        int $height = 600
    ): array {
        $paths = [];
        foreach ($files as $file) {
            $paths[] = self::upload($file, $folder, $width, $height);
        }
        return $paths;
    }

    /**
     * Hapus file lama
     */
    public static function delete(?string $path): void
    {
        if (!$path) return;
        $full = storage_path("app/public/{$path}");
        if (file_exists($full)) {
            unlink($full);
        }
    }
}
