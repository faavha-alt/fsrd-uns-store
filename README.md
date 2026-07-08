# FSRD UNS Store
### Platform Layanan Eksternal Fakultas Seni Rupa dan Desain — Universitas Sebelas Maret

> Dikembangkan oleh **Hexa Sinergy Studio** © 2026

---

## 📋 Tentang Proyek

Platform e-commerce resmi FSRD UNS yang memfasilitasi penjualan karya seni dosen & mahasiswa serta booking kelas pelatihan secara digital. Dikelola terpusat oleh tim Fakultas melalui sistem Admin dan Kurator.

---

## ✨ Fitur Utama

### 🛍️ Lapak Seni & Desain
- Katalog produk dengan filter kategori, harga, dan sorting
- Detail produk dengan galeri foto dan profil kreator
- Keranjang belanja & checkout dengan kode unik transfer
- Upload bukti transfer & verifikasi pembayaran oleh admin

### 🎓 Pelatihan
- Listing kelas pelatihan dengan filter dan jadwal
- Sistem booking dengan data peserta lengkap
- PDF bukti booking yang bisa didownload
- Manajemen kuota peserta otomatis

### 👤 Autentikasi
- Login & Register buyer
- Google OAuth (Login dengan Google)
- Lupa password via email
- Role: Admin, Kurator, Buyer

### 🔔 Notifikasi
- Email otomatis (order masuk, konfirmasi, penolakan)
- Bell notification in-app di dashboard admin
- Konfigurasi SMTP dari dashboard (tanpa edit kode)

### 📊 Admin Panel
- Dashboard statistik real-time
- CRUD produk, pelatihan, kategori, kreator
- Workflow approval produk & pelatihan
- Verifikasi order & booking
- Export laporan Excel (order & booking)
- Manajemen user & rekening bank
- Site settings & email settings dari database
- Log aktivitas admin
- Google OAuth settings dari dashboard

### 🔐 Keamanan
- URL login admin tersembunyi
- Rate limiting login (5x → lock 15 menit)
- Session timeout otomatis (2 jam)
- APP_DEBUG=false di production

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 13, PHP 8.5 |
| Frontend | Blade Templates, CSS Custom |
| Database | MySQL 8 |
| Auth | Laravel Sanctum + Socialite (Google) |
| Email | SMTP (configurable dari DB) |
| PDF | DomPDF |
| Excel | Maatwebsite/Excel |
| Image | Intervention Image v3 |
| Server | VPS Ubuntu 24 + CloudPanel |

---

## 🚀 Instalasi

### Requirements
- PHP >= 8.2
- Composer
- MySQL 8
- Node.js 20 (via nvm)

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/faavha-alt/fsrd-uns-store.git
cd fsrd-uns-store

# 2. Install dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Setup database di .env
DB_DATABASE=fsrduns
DB_USERNAME=your_username
DB_PASSWORD=your_password

# 6. Jalankan migrasi & seeder
php artisan migrate
php artisan db:seed

# 7. Storage link
php artisan storage:link

# 8. Optimasi
php artisan optimize
```

---

## ⚙️ Konfigurasi

### Environment Variables Penting

```env
APP_NAME="FSRD UNS Store"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_DATABASE=fsrduns

CACHE_STORE=database
SESSION_DRIVER=database

# Google OAuth (bisa diatur dari Admin Panel)
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
```

### Konfigurasi dari Admin Panel
Pengaturan berikut **tidak perlu edit `.env`** — bisa diubah langsung dari dashboard:
- ✅ SMTP Email (host, port, username, password)
- ✅ Google OAuth (Client ID, Secret, Redirect URI)
- ✅ Notifikasi email per event (on/off)
- ✅ Site Settings (nama, logo, hero, kontak, sosmed)

---

## 👥 Role Pengguna

| Role | Akses |
|------|-------|
| **Admin** | Akses penuh — approve produk, verifikasi transaksi, kelola user, settings |
| **Kurator** | Kelola konten — upload produk & kelas, input kreator |
| **Buyer** | Beli produk, booking pelatihan, riwayat transaksi |

### Default Admin
```
URL    : /management-fsrd/masuk
Email  : admin@fsrd.uns.ac.id
```
> ⚠️ Password diset saat instalasi via `php artisan tinker`

---

## 📁 Struktur Direktori

```
app/
├── Console/Commands/       # Artisan commands
├── Enums/                  # UserRole enum
├── Exports/                # Excel export classes
├── Helpers/                # MailHelper, ImageHelper, NotificationHelper
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin panel controllers
│   │   └── Auth/           # Authentication controllers
│   └── Middleware/         # Custom middleware
├── Models/                 # Eloquent models
└── Mail/                   # Mailable classes

resources/
├── views/
│   ├── admin/              # Admin panel views
│   ├── buyer/              # Buyer auth views
│   ├── emails/             # Email templates
│   ├── errors/             # Custom error pages
│   └── layouts/            # Layout templates

public/
└── css/
    └── frontend.css        # Single CSS file (frontend + admin)
```

---

## 📧 Email Notifikasi

| Event | Penerima |
|-------|---------|
| Order baru masuk | Admin |
| Booking baru masuk | Admin |
| Order dikonfirmasi | Buyer |
| Order ditolak | Buyer |
| Booking dikonfirmasi | Peserta |
| Booking ditolak | Peserta |
| Reset password | Buyer |

---

## 🗺️ Routes Penting

```
/                           → Beranda
/lapak                      → Katalog produk
/pelatihan                  → Daftar kelas pelatihan
/kreator                    → Daftar kreator publik
/tentang                    → Halaman tentang
/cara-pembelian             → Panduan pembelian
/login-buyer                → Login buyer
/register                   → Register buyer
/auth/google                → Login dengan Google
/akun                       → Akun buyer (tab pesanan & booking)
/management-fsrd/masuk      → Login admin (URL rahasia)
/admin/dashboard            → Dashboard admin
/admin/reports              → Laporan & export Excel
/admin/email-settings       → Pengaturan email & Google OAuth
```

---

## 📦 Package yang Digunakan

```json
{
    "laravel/framework": "^13.0",
    "laravel/socialite": "^5.0",
    "intervention/image": "^3.0",
    "barryvdh/laravel-dompdf": "^3.0",
    "maatwebsite/excel": "^3.1"
}
```

---

## 🔒 Keamanan

- URL login admin tersembunyi (`/management-fsrd/masuk`)
- Rate limiting: max 5x login gagal → lock 15 menit
- Session timeout admin: 2 jam tidak aktif
- CSRF protection di semua form
- Password di-hash dengan bcrypt
- `APP_DEBUG=false` di production
- Log aktivitas login/logout admin

---

## 📄 Lisensi

Proyek ini dikembangkan untuk **Fakultas Seni Rupa dan Desain, Universitas Sebelas Maret**.  
Hak cipta dilindungi. Tidak untuk didistribusikan tanpa izin.

---

## 👨‍💻 Developer

**Hexa Sinergy Studio**  
Project Manager: Zulfa Nurul Hakim  
© 2026 — All Rights Reserved
