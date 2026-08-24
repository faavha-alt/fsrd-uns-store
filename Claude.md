# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

# CLAUDE.md — FSRD UNS Store
## Panduan Konteks untuk Claude Code

---

## 🎯 Overview Proyek

**Nama:** Platform Layanan Eksternal FSRD UNS Store  
**Client:** Fakultas Seni Rupa dan Desain, Universitas Sebelas Maret  
**Developer:** Hexa Sinergy Studio (Project Manager: Zulfa Nurul Hakim)  
**Status:** Production — Phase 1 Complete  
**URL:** https://project.favha.cloud  
**GitHub:** https://github.com/faavha-alt/fsrd-uns-store.git  

Platform e-commerce resmi FSRD UNS untuk penjualan karya seni dosen & mahasiswa dan booking kelas pelatihan secara digital.

---

## 📌 Status & Progress Terkini

> **PENTING:** Baca bagian ini duluan setiap buka project — supaya langsung tahu sudah sampai mana, tanpa perlu re-derive dari `git log`.
> Setiap selesai mengerjakan sesuatu yang cukup besar, **update bagian ini** (jangan cuma andalkan commit message).

**Commit terakhir yang tercermin di dokumen ini:** `b992f95` (sudah di-push ke GitHub 2026-08-24 — **BELUM** di-deploy/`git pull` ke server produksi `project.favha.cloud`, yang masih di `fed0f56`)
**Status:** Production, sudah live di https://project.favha.cloud

> ⚠️ **WAJIB sebelum deploy commit yang menambahkan web installer (lihat checklist di bawah)** ke server produksi yang sudah live: buat file kosong `storage/installed.lock` di server (`touch storage/installed.lock` via SSH) **sebelum atau tepat sesudah** `git pull`. File ini sengaja di-gitignore (tidak ikut ter-clone ke server baru, supaya wizard `/install` bisa jalan di server baru) — tapi kalau tidak dibuat di server produksi yang sudah berjalan, middleware `EnsureInstalled` akan mengira aplikasi belum ter-install dan me-redirect semua traffic ke `/install`, berpotensi membuka form re-konfigurasi database di depan situs yang sudah live. Ini satu-satunya perintah shell yang masih dibutuhkan, dan hanya sekali (transisi), bukan kebutuhan rutin.

### Sudah selesai (checklist)
- [x] **Phase 1** — platform inti: katalog produk (lapak), pelatihan, cart, checkout kode unik, booking kelas, admin panel (CRUD produk/pelatihan/kategori/kreator/user/rekening), auth admin + buyer, approval workflow, email notifikasi
- [x] Multi-image produk + lightbox galeri di halaman detail
- [x] Link marketplace eksternal di detail produk (model `Marketplace`)
- [x] Mobile responsive (breakpoint 768px/1024px)
- [x] Bell notifikasi in-app di dashboard admin (`NotificationHelper`)
- [x] Export laporan Excel (order & booking) via `maatwebsite/excel`
- [x] Google OAuth — login buyer + konfigurasi dari Admin Panel (`admin/email-settings`)
- [x] Perbaikan UX alur booking
- [x] Quill editor styling untuk deskripsi produk + rework layout form create/edit produk admin
- [x] **Hardening produksi (2026-08-18)**: `APP_DEBUG` dimatikan di server, 21 CVE di dependency (dompdf/guzzle/commonmark/phpspreadsheet) di-patch, auth deploy server→GitHub dipindah dari PAT plaintext ke SSH deploy key, dependency dev (phpunit/mockery/dst) tidak lagi ikut ter-install di server, `intervention/image` (tidak terpakai) dihapus, scaffolding Tailwind/Vite/`welcome.blade.php` yang tidak terpakai dihapus dari repo
- [x] **Web installer (2026-08-24, commit `b992f95`, sudah di-push)**: wizard instalasi ala WordPress di `/install` (`app/Http/Controllers/InstallController.php`, view `resources/views/install/*`) — cek requirement server, input kredensial DB (tes koneksi via PDO + auto `CREATE DATABASE IF NOT EXISTS`), tulis `.env` (`app/Helpers/EnvHelper.php`), jalankan `key:generate`+`migrate`+`storage:link` lewat `Artisan::call()`, buat akun Admin pertama — semua dari browser, tanpa SSH. Ditandai selesai lewat `storage/installed.lock` (gitignored) yang dicek oleh middleware `EnsureInstalled` (global, di `bootstrap/app.php`) untuk redirect ke `/install` selama lock belum ada, dan mengunci `/install` setelah lock ada. Sebelum lock ada, `AppServiceProvider::register()` memaksa `session`/`cache`/`queue` pakai driver `file`/`sync` (DB belum tentu ada) — lihat detail & pitfall di bawah. `public/index.php` juga otomatis meng-copy `.env.example` → `.env` (dengan `APP_KEY` acak) kalau `.env` belum ada, supaya halaman `/install` bisa tampil sama sekali. **Belum pernah diklik langsung di browser** (PHP lokal dev 8.2, project butuh ^8.3 — tervalidasi lewat `php -l` tiap file + tracing manual siklus request + `composer install --no-dev` sukses beneran di server produksi PHP 8.4 saat bikin master zip di bawah, tapi wizard-nya sendiri belum pernah benar-benar dijalankan sampai selesai di server sungguhan). Coba klik-per-klik dulu di server/lingkungan ber-PHP 8.3+ sebelum dipakai serius untuk pindah server produksi.

  **Master zip siap pakai untuk instalasi server baru** dibuat dari commit `581a0e4` (bukan dari `fed0f56` yang masih live di produksi) — lihat `scripts/build-release.sh` untuk cara bikin ulang. Lokasi file terakhir: scratchpad sesi Claude Code yang membuatnya (`fsrd-uns-store-581a0e4.zip`, 16MB) — kalau sudah tidak ada, generate ulang dengan script itu dari commit yang sama atau lebih baru. **`Claude.md` dan `tests/` TIDAK ikut** di zip ini (lihat `.gitattributes` — `export-ignore`, ditambahkan 2026-08-24 setelah revisi pertama zip ini sempat tanpa sadar menyertakan `Claude.md` yang isinya detail operasional produksi/SSH/IP server — jangan sampai terulang, selalu cek `unzip -l <zip> | grep -i claude` setelah build kalau ragu).
- [x] **Sistem lisensi anti-pindah-tangan (2026-08-24)**: wizard `/install` sekarang minta license key di step pertama (`app/Http/Controllers/InstallController.php::license()/licenseStore()`, view `resources/views/install/license.blade.php`) yang diaktivasi ke **license server terpisah** (lihat di bawah) — key otomatis terkunci ke domain yang aktivasi pertama kali. Setelah terinstall, middleware `EnsureLicensed` (global, di `bootstrap/app.php`, setelah `EnsureInstalled`) mengecek status lisensi secara berkala (throttled, max 1x/jam via `Cache::add`) lewat `app/Helpers/LicenseHelper.php`, disimpan di `Setting::get('license_status')`/`license_last_check_at`. Kalau domain berubah (dipindah tanpa izin) atau di-revoke dari sisi kita → **situs dikunci total** (halaman `resources/views/license-locked.blade.php`, HTTP 503) — untuk gangguan jaringan biasa ada masa tenggang `LICENSE_GRACE_DAYS` (default 3 hari, config `config/license.php`) sebelum ikut terkunci. Ada juga `php artisan license:check` (dipanggil manual/cron) dan `php artisan license:activate {key?}` (retrofit instalasi lama).

  **PENTING — perilaku "belum dikonfigurasi" berubah 2026-08-24**: awalnya kalau `LICENSE_SERVER_URL`/`LICENSE_API_SECRET` kosong di `.env`, wizard `/install` **diam-diam skip** step lisensi (niatnya supaya dev lokal/instalasi lama tidak rusak) — ini bug nyata: karena `.env.example` di repo publik ini sengaja TIDAK menyimpan secret asli (dikomentari), fresh install manapun otomatis auto-copy `.env` tanpa config lisensi → **lolos tanpa diminta key sama sekali**. Sudah diperbaiki (`InstallController::licenseServerConfigured()`) — sekarang kalau server/secret belum dikonfigurasi, wizard **berhenti total** dengan pesan error, TIDAK bisa lanjut instalasi. Nilai asli `LICENSE_SERVER_URL`/`LICENSE_API_SECRET` hanya disuntikkan ke `.env.example` di dalam paket zip saat build (`scripts/build-release.sh`, lewat env var, TIDAK PERNAH di-commit ke git) — jadi git clone mentah dari GitHub tetap aman (tidak bisa dipakai install tanpa lewat proses build resmi), tapi zip resmi dari `build-release.sh` sudah langsung jalan. Middleware runtime `EnsureLicensed` (bukan installer) TETAP sengaja no-op kalau belum dikonfigurasi — itu khusus untuk kompatibilitas mundur instalasi lama (produksi `project.favha.cloud`) yang predate fitur ini, bukan bug.

  **License server-nya (`activate.php`/`check.php`/`admin.php`, PHP polos + MySQL, tanpa dependency Composer, TIDAK ada di repo ini secara sengaja)** sudah live di **https://lisensi.mipa.uns.ac.id**, source lokalnya di `D:\fsrd-license-server\` (sibling folder, bukan di dalam `fsrd-uns-store`). Ada dashboard admin di `/admin.php` (stats, filter/search per app/status, catatan internal per key, revoke/reactivate/reset-domain/delete) — HTTP Basic Auth. Database MySQL (`db lisensi`, user `lisensi`, host `127.0.0.1` — kredensial di `app/config.php` server itu, chmod 600, jangan pernah taruh di repo manapun) dipilih setelah awalnya sempat pakai SQLite (data lama diarsipkan di `app/licenses.sqlite.bak-migrated-to-mysql` di server). Sudah menerbitkan & mengaktivasi 1 key untuk `fsrd-uns-store` terkunci ke domain `project.favha.cloud`. Akses SSH ke server lisensi: `ssh -p 1103 lisensi@lisensi.mipa.uns.ac.id` pakai key `~/.ssh/lisensi_mipa_uns` (dibuat 2026-08-24, sudah diotorisasi user lewat panel hosting — bukan password). Detail struktur folder & alur kerja (revoke/reset-domain untuk pindah server yang sah) ada di `D:\fsrd-license-server\README.md`.

  > ⚠️ **WAJIB ditambahkan ke `.env` produksi `project.favha.cloud`** (server ini belum lewat wizard `/install`, jadi retrofit manual): `LICENSE_KEY`, `LICENSE_SERVER_URL=https://lisensi.mipa.uns.ac.id`, `LICENSE_API_SECRET` (nilai persis sama dengan `api_secret` di `app/config.php` milik license server — lihat riwayat percakapan 2026-08-24 atau `D:\fsrd-license-server\app\config.php` kalau masih ada akses). Setelah `.env` diisi, jalankan `php artisan license:activate` sekali (atau cukup tunggu — `EnsureLicensed` akan self-heal di request admin berikutnya). **Belum dilakukan di server produksi** — sampai ini dikerjakan, middleware `EnsureLicensed` di produksi akan no-op (karena `LICENSE_SERVER_URL` masih kosong di `.env` produksi), jadi situs TIDAK akan terkunci sendiri, tapi juga belum terlindungi.

### Belum ada / belum diketahui
- Belum ada roadmap Phase 2 tertulis di repo — kalau user minta fitur baru, cek dulu apakah sudah tercakup di atas sebelum asumsi ini "belum ada".
- Token GitHub PAT lama (yang sempat plaintext di `.git/config` server) — belum terkonfirmasi sudah di-revoke manual oleh user di GitHub Settings.
- Belum ada test coverage nyata (`tests/` masih file contoh bawaan Laravel — lihat bagian Testing).

Kalau `git log` menunjukkan commit setelah `b1e457d` yang belum tercatat di sini, checklist di atas kemungkinan sudah usang — cross-check dan update.

---

## 🛠️ Tech Stack

```
Backend:    Laravel 13, PHP 8.5.7
Database:   MySQL 8 (db: fsrduns)
Frontend:   Blade Templates + CSS Custom (TANPA Tailwind, TANPA Bootstrap)
JavaScript: Vanilla JS only (TANPA React, Vue, Alpine, Livewire)
CSS:        Single file → public/css/frontend.css
Server:     VPS Ubuntu 24 + CloudPanel
User:       favha-project
Path:       /home/favha-project/htdocs/project.favha.cloud
```

### Library yang Digunakan
```
laravel/socialite      → Google OAuth
barryvdh/laravel-dompdf → PDF Bukti Booking
maatwebsite/excel      → Export laporan Excel
quill.js 1.3.6        → Rich text editor (CDN, bukan npm)

# Image processing TIDAK pakai library — pakai fungsi GD langsung (imagecreatefromstring, dll)
# di app/Helpers/ImageHelper.php. intervention/image sempat ter-install tapi tidak pernah
# dipakai di kode — sudah di-composer-remove (Agustus 2026).
```

---

## ⚠️ Aturan Penting (WAJIB DIIKUTI)

### Yang TIDAK BOLEH digunakan:
- ❌ Filament, AdminLTE, Jetstream
- ❌ Tailwind CSS build pipeline
- ❌ Livewire, Alpine.js, Vue, React
- ❌ `npm run dev` atau build process apapun
- ❌ `Intervention\Image\Laravel\Facades\Image` (tidak kompatibel dengan v4)

### Yang HARUS digunakan:
- ✅ Blade templates + inline CSS/style
- ✅ Vanilla JavaScript
- ✅ CSS di `public/css/frontend.css` (satu file)
- ✅ GD functions langsung untuk image processing (`imagecreatefromstring`, `imagecopyresampled`)
- ✅ Complete files, bukan partial snippets

### Image Processing — GUNAKAN CARA INI:
```php
// ✅ BENAR — GD langsung
$source = imagecreatefromstring(file_get_contents($file->getRealPath()));
// flatten transparency
$flat = imagecreatetruecolor($srcW, $srcH);
imagefill($flat, 0, 0, imagecolorallocate($flat, 255, 255, 255));
imagecopy($flat, $source, 0, 0, 0, 0, $srcW, $srcH);
imagejpeg($dest, $path, 85);

// ❌ SALAH — Intervention Image facade tidak jalan di v4
Image::read($file)->cover(800, 600)->save($path);
```

---

## 📁 Struktur Direktori Penting

```
app/
├── Console/Commands/          # Artisan commands
├── Enums/UserRole.php         # Admin, Kurator, Buyer
├── Exports/
│   ├── OrdersExport.php       # Export order ke Excel
│   └── BookingsExport.php     # Export booking ke Excel
├── Helpers/
│   ├── ImageHelper.php        # Upload, resize, delete gambar (GD)
│   ├── MailHelper.php         # Configure SMTP dari DB, send email
│   └── NotificationHelper.php # Bell notifikasi in-app (Cache)
├── Http/
│   ├── Controllers/
│   │   ├── Admin/             # Semua controller admin panel
│   │   │   ├── DashboardController.php
│   │   │   ├── ProductController.php
│   │   │   ├── TrainingClassController.php
│   │   │   ├── OrderController.php
│   │   │   ├── BookingController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── CreatorController.php
│   │   │   ├── BankAccountController.php
│   │   │   ├── UserController.php
│   │   │   ├── SettingController.php
│   │   │   ├── EmailSettingController.php
│   │   │   ├── MarketplaceController.php
│   │   │   ├── ReportController.php
│   │   │   └── ActivityLogController.php
│   │   └── Auth/
│   │       ├── LoginController.php      # Admin login + rate limiting
│   │       ├── BuyerAuthController.php  # Buyer login/register
│   │       ├── GoogleAuthController.php # Google OAuth
│   │       └── ForgotPasswordController.php
│   └── Middleware/
│       ├── EnsureAdmin.php
│       ├── EnsureBuyer.php
│       ├── EnsureAdminOrKurator.php
│       └── AdminSessionTimeout.php      # 2 jam timeout
├── Mail/                      # 6 Mailable classes
│   ├── OrderPlaced.php        # → Admin
│   ├── OrderConfirmed.php     # → Buyer
│   ├── OrderRejected.php      # → Buyer
│   ├── BookingPlaced.php      # → Admin
│   ├── BookingConfirmed.php   # → Buyer (peserta)
│   └── BookingRejected.php    # → Buyer (peserta)
└── Models/
    ├── User.php               # role: Admin/Kurator/Buyer, google_id, is_active
    ├── Product.php            # images: array cast, status: pending/approved/rejected
    ├── TrainingClass.php      # status: pending/approved/rejected
    ├── ClassSchedule.php      # date, start_time, end_time, quota, booked_count
    ├── Order.php              # order_number, buyer_*, subtotal, unique_code, total
    ├── OrderItem.php          # product_name, quantity, price, subtotal
    ├── Booking.php            # booking_number, participant_*, total, unique_code
    ├── Creator.php            # type: dosen/mahasiswa
    ├── Category.php           # type: produk/pelatihan
    ├── BankAccount.php        # is_active
    ├── Setting.php            # key-value store, static get/set dengan Cache
    └── Marketplace.php        # name, url, icon, is_active, sort_order

resources/views/
├── layouts/
│   ├── app.blade.php          # Layout frontend publik
│   └── admin.blade.php        # Layout admin panel
├── admin/                     # Semua view admin
│   ├── dashboard.blade.php
│   ├── dashboard-kurator.blade.php
│   ├── products/create.blade.php    # Quill.js untuk deskripsi
│   ├── products/edit.blade.php      # Quill.js untuk deskripsi
│   ├── email-settings/index.blade.php # Tab SMTP + Google OAuth
│   ├── marketplaces/index.blade.php
│   ├── reports/index.blade.php
│   └── activity-log/index.blade.php
├── buyer/
│   ├── auth/login.blade.php         # Standalone (extends nothing)
│   ├── auth/register.blade.php      # Standalone
│   ├── auth/forgot-password.blade.php
│   ├── auth/reset-password.blade.php
│   └── account.blade.php            # Tab: Pesanan & Booking
├── lapak/
│   ├── index.blade.php        # Katalog + filter + search
│   └── show.blade.php         # Detail produk + lightbox + marketplace links
├── pelatihan/
│   ├── index.blade.php
│   └── show.blade.php
├── booking/
│   ├── create.blade.php       # Form booking + kode unik + info rekening
│   ├── success.blade.php
│   └── pdf.blade.php          # Template PDF bukti booking
├── cart/
│   ├── index.blade.php        # Keranjang belanja
│   └── success.blade.php      # Halaman sukses order
├── creator/
│   ├── index.blade.php        # Daftar kreator publik
│   └── show.blade.php         # Profil kreator + karya
├── emails/                    # Template email HTML
│   ├── order-placed.blade.php
│   ├── order-confirmed.blade.php
│   ├── order-rejected.blade.php
│   ├── booking-placed.blade.php
│   ├── booking-confirmed.blade.php
│   ├── booking-rejected.blade.php
│   └── reset-password.blade.php
├── errors/
│   ├── 404.blade.php
│   └── 500.blade.php
├── home.blade.php
├── tentang.blade.php
└── cara-pembelian.blade.php

public/css/frontend.css        # SINGLE CSS FILE — semua styling di sini
```

---

## 🗄️ Database Schema

### Tabel Utama

```sql
users               id, name, email, password, role(enum), is_active, phone, google_id
categories          id, name, slug, type(produk|pelatihan)
creators            id, name, type(dosen|mahasiswa), department, bio, photo
products            id, name, slug, category_id, creator_id, curator_id, description(HTML),
                    price, stock, images(json array), status(pending|approved|rejected),
                    rejection_reason
training_classes    id, name, slug, category_id, creator_id, curator_id, description,
                    syllabus, price, image, status(pending|approved|rejected)
class_schedules     id, training_class_id, date, start_time, end_time, location,
                    quota, booked_count
orders              id, order_number, user_id, buyer_name, buyer_email, buyer_phone,
                    subtotal, unique_code, total, payment_proof, bank_account_id,
                    status(pending_payment|pending_verification|confirmed|rejected|completed),
                    rejection_reason, verified_by
order_items         id, order_id, product_id, product_name, quantity, price, subtotal
bookings            id, booking_number, user_id, schedule_id, participant_name,
                    participant_email, participant_phone, institution, total, unique_code,
                    payment_proof, bank_account_id,
                    status(pending_payment|pending_verification|confirmed|rejected),
                    rejection_reason
bank_accounts       id, bank_name, account_number, account_name, is_active
settings            id, key, value (key-value store untuk semua konfigurasi)
marketplaces        id, name, url, icon, is_active, sort_order
password_reset_tokens email, token, created_at
```

### Setting Keys Penting
```
site_name, site_tagline, site_description, site_logo
hero_title, hero_subtitle, hero_image
contact_email, contact_wa, contact_address
instagram_url, youtube_url, facebook_url, twitter_url
about_title, about_description, about_history, about_vision, about_mission
mail_host, mail_port, mail_username, mail_password, mail_encryption
mail_from_name, mail_from_address, notif_admin_email
notif_admin_order_enabled, notif_admin_booking_enabled
notif_buyer_order_confirmed, notif_buyer_order_rejected
notif_buyer_booking_confirmed, notif_buyer_booking_rejected
google_oauth_enabled, google_client_id, google_client_secret, google_redirect_uri
```

---

## 🔐 Keamanan & Akses

### SSH ke server produksi (2026-08-24)
`project.favha.cloud` di-proxy Cloudflare (orange-cloud) — domain publiknya **tidak bisa** dipakai untuk SSH (semua port selain 80/443 di-drop diam-diam oleh Cloudflare, bukan ditolak). Akses langsung ke origin server lewat **Tailscale**:
```
ssh -i ~/.ssh/project_favha_cloud favha-project@10.11.12.30   # port 22, IP internal Tailscale (bukan tailnet IP 100.x)
```
Key dibuat & diotorisasi 2026-08-24 (public key ditambahkan user lewat CloudPanel). Tailscale sudah terpasang & aktif di mesin dev (MagicDNS terdeteksi otomatis) — kalau di sesi lain `ping 10.11.12.30` gagal, cek dulu status Tailscale (`tailscale status`) sebelum asumsi server down.

### URL Admin (Rahasia)
```
URL Login: /management-fsrd/masuk
```

### Role System
```php
// app/Enums/UserRole.php
enum UserRole: string {
    case Admin   = 'admin';
    case Kurator = 'kurator';
    case Buyer   = 'buyer';
}
```

### Middleware Alias (bootstrap/app.php)
```php
'buyer'            => EnsureBuyer::class
'admin'            => EnsureAdmin::class
'admin.or.kurator' => EnsureAdminOrKurator::class
'admin.timeout'    => AdminSessionTimeout::class  // 2 jam timeout
```

### Rate Limiting Login
- Max 5x gagal → lock 15 menit
- Key: `login.{email}.{ip}`

---

## 🛣️ Routes Utama (routes/web.php)

```php
// PUBLIC
GET  /                          home
GET  /lapak                     lapak.index
GET  /lapak/{product:slug}      lapak.show
GET  /pelatihan                 pelatihan.index
GET  /pelatihan/{class:slug}    pelatihan.show
GET  /kreator                   creator.index
GET  /kreator/{creator}         creator.show
GET  /tentang                   tentang
GET  /cara-pembelian            cara-pembelian

// CART (tanpa login)
GET  /keranjang                 cart.index
POST /keranjang/tambah/{product} cart.add
POST /keranjang/update/{id}     cart.update
POST /keranjang/hapus/{id}      cart.remove

// AUTH ADMIN
GET|POST /management-fsrd/masuk  login / login.submit
POST     /logout                  logout

// AUTH BUYER
GET|POST /login-buyer            buyer.login / buyer.login.submit
GET|POST /register               buyer.register / buyer.register.submit
POST     /logout-buyer           buyer.logout
GET|POST /lupa-password          buyer.forgot-password / buyer.forgot-password.send
GET|POST /reset-password/{token} buyer.reset-password / buyer.reset-password.update
GET      /auth/google            auth.google
GET      /auth/google/callback   auth.google.callback

// BUYER (middleware: buyer)
GET|POST /checkout               cart.checkout / cart.placeOrder
GET      /booking/sukses/{no}    booking.success    ← HARUS SEBELUM /{schedule}
GET      /booking/bukti/{no}     booking.pdf        ← HARUS SEBELUM /{schedule}
GET|POST /booking/{schedule}     booking.create / booking.store
GET      /akun                   buyer.account
GET      /akun/orders/{no}       buyer.order.detail

// ADMIN (middleware: auth + admin.timeout)
// Admin + Kurator:
GET      /admin/dashboard        admin.dashboard
RESOURCE /admin/products         admin.products.*
RESOURCE /admin/training-classes admin.training-classes.*
RESOURCE /admin/categories       admin.categories.*
RESOURCE /admin/creators         admin.creators.*
// Admin Only:
GET|POST /admin/orders           admin.orders.*
GET|POST /admin/bookings         admin.bookings.*
GET      /admin/reports          admin.reports.index
RESOURCE /admin/bank-accounts    admin.bank-accounts.*
RESOURCE /admin/users            admin.users.*
GET|POST /admin/settings         admin.settings.*
GET|POST /admin/email-settings   admin.email-settings.*
RESOURCE /admin/marketplaces     admin.marketplaces.*
GET      /admin/activity-log     admin.activity-log.index
GET      /admin/notifications/read-all  admin.notifications.read-all
```

---

## 💡 Pattern & Konvensi

### Setting Model — Cara Pakai
```php
// Ambil setting
\App\Models\Setting::get('site_name', 'Default Value');

// Set setting
\App\Models\Setting::set('site_name', 'FSRD UNS Store');
```

### MailHelper — Cara Pakai
```php
// Konfigurasi SMTP dari DB sebelum kirim email
\App\Helpers\MailHelper::configure();

// Cek apakah notifikasi aktif
\App\Helpers\MailHelper::isEnabled('notif_admin_order_enabled');

// Ambil email admin
\App\Helpers\MailHelper::adminEmail();
```

### NotificationHelper — Cara Pakai
```php
// Tambah notifikasi (disimpan di Cache database)
\App\Helpers\NotificationHelper::add('order', 'Pesan notifikasi', '/admin/orders/1');

// Ambil semua notifikasi
\App\Helpers\NotificationHelper::getAll();

// Jumlah yang belum dibaca
\App\Helpers\NotificationHelper::getUnreadCount();
```

### ImageHelper — Cara Pakai
```php
// Upload single image
$path = \App\Helpers\ImageHelper::upload($file, 'products', 800, 600);

// Upload multiple
$paths = \App\Helpers\ImageHelper::uploadMultiple($files, 'products', 800, 600);

// Hapus file
\App\Helpers\ImageHelper::delete($path);
\App\Helpers\ImageHelper::deleteMultiple($paths);
```

### Ukuran Resize per Jenis
```
Produk:        800x600px  → storage/products/
Kreator:       400x400px  → storage/creators/
Kelas:         800x500px  → storage/training-classes/
Hero/Settings: 1920x600px → storage/settings/
Logo:          400x400px  → storage/settings/
Marketplace:   tidak diresize, store langsung
```

---

## 🎨 CSS Variables (Design System)

```css
--cerulean:       #0E7DA7   /* Warna utama */
--cerulean-dark:  #0A5F80
--cerulean-deeper:#0A3D52
--sky:            #1FABE1   /* Warna sekunder */
--sky-pale:       #E6F7FD
--gold:           #E9A828   /* Warna aksen */
--gold-light:     #FFDB07
--gold-pale:      #FEF9E7
--green:          #10B981
--light-green:    #D1FAE5
--red:            #EF4444
--ink:            #1A1A2E   /* Teks utama */
--muted:          #6B7280   /* Teks sekunder */
--border:         #E5E7EB
--cream:          #F9FAFB
--white:          #FFFFFF

/* Font */
Montserrat → heading, bold, angka
Poppins    → body text, paragraf
```

---

## 🔑 Key Learnings / Pitfalls

### Web Installer — kenapa session/cache dipaksa ke driver `file` sebelum install
```php
// app/Providers/AppServiceProvider.php::register()
if (!file_exists(storage_path('installed.lock'))) {
    config(['session.driver' => 'file', 'cache.default' => 'file', 'queue.default' => 'sync']);
}
```
Default project ini `SESSION_DRIVER=database` & `CACHE_STORE=database` (lihat `.env.example`) — tapi sebelum wizard `/install` selesai, tabel `sessions`/`cache` (bahkan DB-nya) belum tentu ada. Tanpa override ini, request pertama ke server baru langsung crash (session middleware gagal query ke DB yang belum dikonfigurasi). Override ini otomatis berhenti begitu `storage/installed.lock` dibuat di akhir wizard — request setelahnya balik pakai driver `database` sesuai `.env` seperti biasa. Kalau menambah service provider baru yang jalan di `register()`/`boot()` awal, jangan asumsikan DB sudah bisa diakses — cek `file_exists(storage_path('installed.lock'))` dulu kalau perlu.

### Route Ordering — KRITIS
```php
// ✅ BENAR — spesifik SEBELUM wildcard
Route::get('/booking/sukses/{bookingNumber}', ...); // dulu
Route::get('/booking/bukti/{bookingNumber}', ...);  // dulu
Route::get('/booking/{schedule}', ...);              // belakangan

// ❌ SALAH — wildcard duluan akan capture /sukses sebagai {schedule}
Route::get('/booking/{schedule}', ...);
Route::get('/booking/sukses/{bookingNumber}', ...); // tidak akan tercapai
```

### Double Prefix Bug
```php
// ❌ SALAH — menghasilkan /admin/admin/orders
Route::prefix('admin')->name('admin.')->group(function() {
    Route::prefix('admin')->name('admin.')->group(function() { // JANGAN nested!
        Route::get('orders', ...);
    });
});

// ✅ BENAR
Route::middleware(['auth', 'admin.timeout'])->prefix('admin')->name('admin.')->group(function() {
    Route::get('orders', ...)->name('orders.index');
});
```

### Inline Style Override
```php
// CSS class tidak bisa override inline style!
// ❌ Ini tidak akan bekerja:
// .cart-grid { grid-template-columns: 1fr !important; }
// karena view punya: style="display:grid; grid-template-columns:1fr 320px"

// ✅ Solusi: tambahkan class ke elemen
// Di view: <div style="..." class="cart-layout">
// Di CSS: .cart-layout { grid-template-columns: 1fr !important; }
```

### PNG Upload dengan GD
```php
// imagecreatefromstring() otomatis detect format
$source = imagecreatefromstring(file_get_contents($file->getRealPath()));
// Support: JPG, PNG, GIF, WebP, BMP
```

### @push/@stack Pattern
```blade
{{-- Layout HARUS punya: --}}
@stack('styles')   {{-- di <head> --}}
@stack('scripts')  {{-- sebelum </body> --}}

{{-- View menggunakan: --}}
@push('styles') ... @endpush
@push('scripts') ... @endpush
```

### Quill.js Submit
```javascript
// WAJIB: salin konten Quill ke hidden input sebelum submit
document.getElementById('form').addEventListener('submit', function() {
    var content = quill.root.innerHTML;
    if (content === '<p><br></p>') content = ''; // kosong
    document.getElementById('hiddenInput').value = content;
});
```

---

## 🚀 Perintah yang Sering Dipakai

```bash
# Development
php artisan optimize:clear          # Clear semua cache
php artisan migrate                 # Jalankan migrasi baru
php artisan db:seed                 # Isi data dummy
php artisan tinker                  # REPL PHP

# Production
php artisan config:cache            # Cache konfigurasi
php artisan route:cache             # Cache routes
php artisan view:cache              # Cache views
php artisan optimize                # Semua cache sekaligus
php artisan storage:link            # Symlink storage ke public

# Debug
tail -f storage/logs/laravel.log    # Monitor error log
php artisan route:list              # Lihat semua route

# Testing (PHPUnit, DB sqlite in-memory saat test — lihat phpunit.xml)
php artisan test                          # Jalankan semua test
php artisan test --filter=NamaTestMethod  # Jalankan satu test/method
php artisan test tests/Feature/Xyz.php    # Jalankan satu file test

# Lint / format (Laravel Pint, terpasang di composer require-dev)
vendor/bin/pint                     # Format semua file sesuai style
vendor/bin/pint --test              # Cek tanpa mengubah file (dry-run)

# Security & dependency
composer audit --locked             # Cek CVE di composer.lock (composer >=2.4; server pakai 2.2.3, jalankan dari mesin dev)

# Deploy ke server yang SUDAH terinstall (lihat "Status & Progress" di atas untuk cara SSH)
git pull origin main
composer install --no-dev --optimize-autoloader   # WAJIB --no-dev di produksi
php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

### Instalasi di server BARU (pindah server, tanpa akses shell)
Sejak ada web installer (`/install`), setup awal di server baru bisa full lewat browser:
1. Upload seluruh isi repo **termasuk folder `vendor/`** (di-zip lalu extract, atau `composer install` sekali kalau masih ada akses shell) ke document root — `.env` sengaja TIDAK di-upload/di-generate manual.
2. Arahkan domain/document root ke folder `public/`.
3. Buka domain di browser → otomatis redirect ke `/install` → ikuti wizard: cek requirement → isi kredensial DB → isi nama situs & akun Admin pertama → Install.
4. Wizard yang menulis `.env`, generate `APP_KEY`, jalankan migrasi, dan buat akun Admin — tanpa perlu SSH sama sekali.

Satu-satunya keterbatasan: `vendor/` tetap harus ada di server (composer install butuh CLI) — solusinya upload paket rilis yang sudah menyertakan `vendor/`, bukan clone repo mentah.

#### Cara bikin "master zip" paket rilis itu
`scripts/build-release.sh` (dites 2026-08-24 langsung di server produksi lewat SSH via Tailscale — `git archive <ref>` untuk source tracked-only (otomatis TIDAK menyertakan `.env`/isi asli `storage/`/`.git`), lalu `composer install --no-dev --optimize-autoloader` di direktori sementara terpisah, lalu di-zip. **Tidak pernah menjalankan `git checkout`/`pull`/`reset` di working tree asli** — aman dijalankan di produksi yang sedang live sekalipun, walau ref yang di-build beda dari yang sedang live di situ.
```bash
bash scripts/build-release.sh                              # dari HEAD → ~/release-build/fsrd-uns-store-<commit>.zip
bash scripts/build-release.sh origin/main                  # dari commit ter-push terbaru (setelah git fetch), TANPA checkout live
bash scripts/build-release.sh origin/main /path/output/lain
```
Jalankan dari server yang PHP-nya cocok buat build `vendor/` (dites di produksi, PHP 8.4). Kalau mau build dari commit yang belum di-deploy ke server itu (kasus 2026-08-24: web installer & lisensi sudah di-push tapi produksi masih di commit lama), pakai `git fetch origin` dulu lalu `bash scripts/build-release.sh origin/main` — TIDAK perlu (dan sebaiknya jangan buru-buru) men-deploy commit itu ke checkout live server itu sendiri hanya demi bikin zip.

> `package.json`, `vite.config.js`, `resources/js/`, `resources/css/`, `welcome.blade.php`, dan `.npmrc` **sudah dihapus dari repo** (Agustus 2026) — itu semua sisa scaffolding default Laravel yang tidak pernah dipakai (proyek ini murni Blade + `public/css/frontend.css` + vanilla JS, tanpa build step, tanpa `node_modules` di server). Jangan tambahkan lagi kecuali memang mau pindah ke build pipeline.

---

## 📧 Alur Email Notifikasi

```
Order baru masuk    → CartController::placeOrder()   → Mail::to(admin)->send(OrderPlaced)
Order dikonfirmasi  → Admin\OrderController::confirm() → Mail::to(buyer)->send(OrderConfirmed)
Order ditolak       → Admin\OrderController::reject()  → Mail::to(buyer)->send(OrderRejected)
Booking baru        → BookingController::store()      → Mail::to(admin)->send(BookingPlaced)
Booking dikonfirmasi→ Admin\BookingController::confirm()→ Mail::to(peserta)->send(BookingConfirmed)
Booking ditolak     → Admin\BookingController::reject() → Mail::to(peserta)->send(BookingRejected)

// Selalu configure SMTP dari DB sebelum kirim:
MailHelper::configure();
if (MailHelper::isEnabled('notif_key')) {
    Mail::to($email)->send(new MailClass($model));
}
```

---

## 📱 Responsive Breakpoints

```css
@media (max-width: 768px)  { /* Mobile */ }
@media (min-width: 769px) and (max-width: 1024px) { /* Tablet */ }
/* Desktop: default (tidak perlu media query) */
```

---

## 🗂️ Admin Panel — Fitur per Role

| Fitur | Admin | Kurator |
|-------|-------|---------|
| Dashboard | ✅ Full stats | ✅ Konten sendiri |
| Produk CRUD | ✅ | ✅ |
| Approve/Reject Produk | ✅ | ❌ |
| Pelatihan CRUD | ✅ | ✅ |
| Order & Booking | ✅ | ❌ |
| Management User | ✅ | ❌ |
| Settings | ✅ | ❌ |
| Laporan Excel | ✅ | ❌ |
| Log Aktivitas | ✅ | ❌ |
| Marketplace | ✅ | ❌ |

---

## 🔄 Alur Transaksi

### Order Produk
```
pending_payment → pending_verification → confirmed → completed
                                      ↘ rejected
```

### Booking Pelatihan
```
pending_payment → pending_verification → confirmed
                                      ↘ rejected
```

### Kode Unik Transfer
```
Total = Harga Produk/Pelatihan + Kode Unik (rand 100-999)
Contoh: Rp 450.000 + 287 = Rp 450.287
Tujuan: identifikasi pembayaran di mutasi bank
```

---

*CLAUDE.md ini dibuat untuk membantu Claude Code memahami project FSRD UNS Store secara menyeluruh.*  
*Last updated: Juli 2026 — Hexa Sinergy Studio*