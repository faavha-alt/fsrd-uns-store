<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Upload & resize single image dengan cover crop
     *
     * @param UploadedFile $file
     * @param string $folder — folder di storage/app/public/
     * @param int $width — lebar target
     * @param int $height — tinggi target
     * @param int $quality — kualitas JPG (1-100)
     * @return string — path relatif dari storage/app/public/
     */
    public static function upload(
        UploadedFile $file,
        string $folder,
        int $width   = 800,
        int $height  = 600,
        int $quality = 85
    ): string {
        // Buat folder kalau belum ada
        $path = storage_path("app/public/{$folder}");
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }

        $filename = Str::random(40) . '.jpg';
        $fullPath = "{$path}/{$filename}";

        // Baca gambar — support JPG, PNG, GIF, WebP, BMP
        $content = file_get_contents($file->getRealPath());
        $source  = @imagecreatefromstring($content);

        if (!$source) {
            throw new \Exception(
                "Format gambar tidak didukung: {$file->getClientOriginalName()}"
            );
        }

        // Flatten transparency (PNG/GIF dengan background putih)
        $srcW  = imagesx($source);
        $srcH  = imagesy($source);
        $flat  = imagecreatetruecolor($srcW, $srcH);
        $white = imagecolorallocate($flat, 255, 255, 255);
        imagefill($flat, 0, 0, $white);
        imagecopy($flat, $source, 0, 0, 0, 0, $srcW, $srcH);
        imagedestroy($source);
        $source = $flat;

        // Cover crop — potong dari tengah, tidak distorsi
        [$cropX, $cropY, $cropW, $cropH] = self::getCoverCrop(
            $srcW, $srcH, $width, $height
        );

        // Resize ke ukuran target
        $dest = imagecreatetruecolor($width, $height);
        imagecopyresampled(
            $dest, $source,
            0, 0, $cropX, $cropY,
            $width, $height, $cropW, $cropH
        );

        // Simpan sebagai JPG
        imagejpeg($dest, $fullPath, $quality);
        imagedestroy($source);
        imagedestroy($dest);

        return "{$folder}/{$filename}";
    }

    /**
     * Upload multiple images
     *
     * @param array $files — array of UploadedFile
     * @param string $folder
     * @param int $width
     * @param int $height
     * @param int $quality
     * @return array — array of paths
     */
    public static function uploadMultiple(
        array  $files,
        string $folder,
        int    $width   = 800,
        int    $height  = 600,
        int    $quality = 85
    ): array {
        $paths = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $paths[] = self::upload($file, $folder, $width, $height, $quality);
            }
        }
        return $paths;
    }

    /**
     * Resize gambar yang sudah ada di storage
     *
     * @param string $existingPath — path relatif dari storage/app/public/
     * @param int $width
     * @param int $height
     * @param int $quality
     * @return string — path baru
     */
    public static function resize(
        string $existingPath,
        int    $width   = 800,
        int    $height  = 600,
        int    $quality = 85
    ): string {
        $fullPath = storage_path("app/public/{$existingPath}");

        if (!file_exists($fullPath)) {
            throw new \Exception("File tidak ditemukan: {$existingPath}");
        }

        $content = file_get_contents($fullPath);
        $source  = @imagecreatefromstring($content);

        if (!$source) {
            throw new \Exception("Gagal membaca gambar: {$existingPath}");
        }

        $srcW = imagesx($source);
        $srcH = imagesy($source);

        // Flatten transparency
        $flat  = imagecreatetruecolor($srcW, $srcH);
        $white = imagecolorallocate($flat, 255, 255, 255);
        imagefill($flat, 0, 0, $white);
        imagecopy($flat, $source, 0, 0, 0, 0, $srcW, $srcH);
        imagedestroy($source);
        $source = $flat;

        [$cropX, $cropY, $cropW, $cropH] = self::getCoverCrop(
            $srcW, $srcH, $width, $height
        );

        $dest = imagecreatetruecolor($width, $height);
        imagecopyresampled(
            $dest, $source,
            0, 0, $cropX, $cropY,
            $width, $height, $cropW, $cropH
        );

        imagejpeg($dest, $fullPath, $quality);
        imagedestroy($source);
        imagedestroy($dest);

        return $existingPath;
    }

    /**
     * Hapus file dari storage
     *
     * @param string|null $path — path relatif dari storage/app/public/
     */
    public static function delete(?string $path): void
    {
        if (!$path) return;

        $full = storage_path("app/public/{$path}");
        if (file_exists($full) && is_file($full)) {
            unlink($full);
        }
    }

    /**
     * Hapus multiple files
     *
     * @param array|null $paths
     */
    public static function deleteMultiple(?array $paths): void
    {
        if (!$paths) return;
        foreach ($paths as $path) {
            self::delete($path);
        }
    }

    /**
     * Validasi apakah file adalah gambar yang valid
     *
     * @param UploadedFile $file
     * @return bool
     */
    public static function isValidImage(UploadedFile $file): bool
    {
        if (!$file->isValid()) return false;

        $allowedMimes = [
            'image/jpeg', 'image/jpg', 'image/png',
            'image/gif', 'image/webp', 'image/bmp',
        ];

        if (!in_array($file->getMimeType(), $allowedMimes)) return false;

        // Verifikasi isi file benar-benar gambar
        $info = @getimagesize($file->getRealPath());
        return $info !== false;
    }

    /**
     * Dapatkan info gambar (width, height, type)
     *
     * @param UploadedFile $file
     * @return array|null
     */
    public static function getImageInfo(UploadedFile $file): ?array
    {
        $info = @getimagesize($file->getRealPath());
        if (!$info) return null;

        return [
            'width'  => $info[0],
            'height' => $info[1],
            'type'   => image_type_to_mime_type($info[2]),
            'size'   => $file->getSize(),
        ];
    }

    /**
     * Hitung koordinat crop untuk cover (crop dari tengah)
     *
     * @return array [cropX, cropY, cropW, cropH]
     */
    private static function getCoverCrop(
        int $srcW, int $srcH,
        int $destW, int $destH
    ): array {
        $srcRatio  = $srcW / $srcH;
        $destRatio = $destW / $destH;

        if ($srcRatio > $destRatio) {
            // Gambar lebih lebar — crop kiri & kanan
            $cropH = $srcH;
            $cropW = (int)($srcH * $destRatio);
            $cropX = (int)(($srcW - $cropW) / 2);
            $cropY = 0;
        } else {
            // Gambar lebih tinggi — crop atas & bawah
            $cropW = $srcW;
            $cropH = (int)($srcW / $destRatio);
            $cropX = 0;
            $cropY = (int)(($srcH - $cropH) / 2);
        }

        return [$cropX, $cropY, $cropW, $cropH];
    }

    /**
     * Generate thumbnail ukuran kecil
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param int $size — ukuran thumbnail (square)
     * @return string
     */
    public static function uploadThumbnail(
        UploadedFile $file,
        string $folder,
        int $size = 300
    ): string {
        return self::upload($file, $folder, $size, $size, 80);
    }
}