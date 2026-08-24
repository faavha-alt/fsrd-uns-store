<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Sebelum wizard /install selesai (storage/installed.lock belum ada),
        // DB & tabel sessions/cache belum tentu ada — paksa pakai driver file
        // supaya request tetap jalan normal tanpa perlu koneksi database.
        if (!file_exists(storage_path('installed.lock'))) {
            config([
                'session.driver' => 'file',
                'cache.default'  => 'file',
                'queue.default'  => 'sync',
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
