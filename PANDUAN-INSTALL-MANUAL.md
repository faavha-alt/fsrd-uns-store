# Panduan Instal Manual — Hosting Tanpa SSH & Tanpa Wizard

Paket ini untuk hosting yang **tidak memberi SSH** dan **tanpa langkah wizard/setup di browser**.
Urutannya: **upload lewat FTP → import `install.sql` di phpMyAdmin → edit `.env` → selesai.**

Isi paket:

```
fsrd-uns-store-<commit>-manual.zip
├── fsrd-uns-store/          → upload ke /home/USER/ (SATU LEVEL DI ATAS public_html)
│   ├── app/ bootstrap/ config/ database/ resources/ routes/
│   ├── vendor/              (sudah termasuk — tidak perlu composer)
│   ├── database/install.sql (schema + akun admin — WAJIB diimport)
│   ├── storage/installed.lock (sudah disiapkan, jangan dihapus)
│   └── .env                 (WAJIB diedit: DB + APP_URL)
├── public_html/             → isi folder ini jadi document root
│   ├── index.php            (sudah dipatch untuk docroot public_html)
│   ├── _extract.php         (helper unzip untuk upload FTP — HAPUS setelah dipakai)
│   └── .htaccess  css/  favicon.ico  robots.txt
├── KREDENSIAL-ADMIN.txt     (email + password admin — HAPUS setelah login)
└── PANDUAN-INSTALL-MANUAL.md
```

## 0. Requirement hosting

| Kebutuhan | Nilai |
|---|---|
| PHP | **8.3 atau 8.4** (bukan 8.2 ke bawah, bukan 8.5) |
| Ekstensi PHP | `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `gd`, `curl`, `zip` |
| Database | MySQL/MariaDB + akses **phpMyAdmin** |
| Akses | FTP saja cukup — tidak butuh SSH/Composer |

## 1. Upload

### Cara A — ada File Manager / cPanel
1. Upload zip ke **home directory** (`/home/USER/`, satu level di atas `public_html`).
2. Extract → otomatis muncul `fsrd-uns-store/` dan isi `public_html/`.

### Cara B — hanya FTP
1. Upload zip **ke dalam** `public_html/`.
2. Upload `_extract.php` (ada di paket) ke `public_html/`.
3. Buka `https://domain-anda/_extract.php` → tunggu sampai muncul **Selesai**.
4. Hapus `_extract.php` dan file `.zip` dari `public_html/`.

### Cara C — upload manual isi folder
Isi `fsrd-uns-store/` → `/home/USER/fsrd-uns-store/`, isi `public_html/` → `/home/USER/public_html/`.

> Jangan upload folder `public/` ke dalam `public_html`. `index.php` di `public_html/` adalah
> versi khusus yang sudah tahu letak folder aplikasi (dan otomatis membuat symlink
> `public_html/storage` agar gambar upload tampil).

## 2. Import database (`install.sql`)

1. Buat database + user lewat panel hosting (kalau belum ada). Catat **nama database, user, password, host**.
2. Buka **phpMyAdmin** → pilih database tadi → tab **Import**.
3. Pilih file `fsrd-uns-store/database/install.sql` (atau upload dari paket) → **Go/Kirim**.
4. Pastikan muncul pesan sukses dan tabel-tabel terbentuk (`users`, `products`, `orders`, `settings`, dll).
5. Isi `install.sql` sudah termasuk: seluruh tabel + **1 akun admin** (lihat `KREDENSIAL-ADMIN.txt`)
   + setting awal `site_name`/`contact_email`.

> Database harus **kosong** waktu import. Kalau sudah ada tabel dari percobaan sebelumnya,
> centang *Drop tables* / hapus dulu tabelnya.

## 3. Edit `.env`

Edit file `fsrd-uns-store/.env` lewat FTP (unduh → edit → unggah kembali). **Empat baris ini wajib:**

```ini
APP_URL=https://domain-kampus-anda.ac.id

DB_DATABASE=nama_database_dari_panel
DB_USERNAME=user_database
DB_PASSWORD=password_database
```

- `APP_KEY` sudah dibuatkan — **jangan diubah/dikosongkan**.
- `DB_HOST` biasanya `localhost` (biarkan).
- `APP_ENV=production` dan `APP_DEBUG=false` sudah diset — biarkan; kalau perlu cari error,
  ubah `APP_DEBUG=true` sementara, lalu kembalikan ke `false`.

## 4. Selesai — cek

1. Buka `https://domain-anda/` → halaman depan tampil (tidak diarahkan ke `/install`).
2. Login admin di `https://domain-anda/management-fsrd/masuk` dengan kredensial di
   `KREDENSIAL-ADMIN.txt`.
3. **Ganti password admin** (menu **User** → edit user admin).
4. Hapus dari server: `public_html/_extract.php`, file `.zip`, dan `KREDENSIAL-ADMIN.txt`.
5. Uji upload 1 gambar produk → harus muncul (bukti symlink `storage` jalan).

## Troubleshooting

**Error 500 setelah `.env` diedit**
- Cek `fsrd-uns-store/storage/logs/laravel.log` (unduh via FTP).
- Pastikan PHP = 8.3/8.4 dan semua ekstensi di atas aktif.
- Pastikan `storage/` & `bootstrap/cache/` bisa ditulis (chmod 755, atau 775 kalau perlu).

**Data tampil tapi session/login gagal**
- Pastikan import `install.sql` sukses (tabel `sessions`, `cache`, `jobs` ada), karena
  `SESSION_DRIVER`/`CACHE_STORE` memakai database.

**Gambar upload tidak muncul (404 `/storage/...`)**
- Cek di File Manager: `public_html/storage` harus berupa symlink ke
  `fsrd-uns-store/storage/app/public` (dibuat otomatis saat halaman pertama dibuka).
- Kalau hosting memblokir `symlink()`, minta hosting mengaktifkannya, atau buat file
  `public_html/link.php` berisi
  `<?php var_dump(symlink(__DIR__.'/../fsrd-uns-store/storage/app/public', __DIR__.'/storage'));`
  buka sekali, lalu hapus.

**Diminta mengisi data lagi dari awal (ingin reset)**
- Hapus isi database, import ulang `install.sql`, dan pastikan `storage/installed.lock` ada.

## Membuat ulang paket ini (mis. setelah ada perubahan kode)

Dari komputer yang punya checkout repo + Composer:

```bash
ADMIN_EMAIL=admin@kampus.ac.id \
ADMIN_PASSWORD='passwordPilihanAnda' \
APP_URL=https://domain-kampus.ac.id \
bash scripts/build-manual-hosting.sh origin/main
```

Menghasilkan `dist/fsrd-uns-store-<commit>-manual.zip`. Jika `ADMIN_PASSWORD` tidak diisi,
password acak dibuatkan dan dicatat di `KREDENSIAL-ADMIN.txt` dalam paket.

`install.sql` di-generate oleh `scripts/generate-install-sql.php` langsung dari file migration
(memakai grammar MySQL Laravel), jadi skemanya identik dengan hasil `php artisan migrate`.
