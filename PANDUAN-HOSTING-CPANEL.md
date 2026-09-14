# Panduan Install di Hosting Kampus (FTP + Database saja, tanpa SSH)

Paket ini dibuat khusus untuk hosting yang **document root-nya terkunci di `public_html/`**
dan **tidak memberi akses SSH/Composer**. Semua langkah di bawah dilakukan lewat FTP +
browser + database yang diberikan pihak hosting.

## 1. Requirement yang WAJIB dicek dulu ke pihak hosting

| Kebutuhan | Nilai |
|---|---|
| PHP | **8.4.x** — minimal **8.4.1**, maksimal **8.4.99** (jangan PHP 8.5) |
| Ekstensi PHP | `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `gd`, `curl` (dan `zip` kalau pakai `_extract.php`) |
| Database | MySQL/MariaDB, 1 database + 1 user (boleh dibuatkan pihak hosting) |
| Akses | FTP (upload file) — SSH **tidak diperlukan** |
| Fungsi PHP | `symlink()` idealnya tidak diblokir (untuk gambar upload). Kalau diblokir, lihat bagian Troubleshooting. |

Kenapa PHP-nya sempit:
- **8.3 ke bawah tidak bisa** — `composer.lock` mengunci Symfony 8 yang butuh PHP >= 8.4.1.
- **8.5 tidak didukung** — `phpoffice/phpspreadsheet` 1.30.x (dipakai export Excel) masih
  mendeklarasikan `< 8.5.0`.

> Sebelum lanjut, set dulu PHP-nya ke **8.4**: di cPanel lewat **MultiPHP Manager** /
> **Select PHP Version**, atau minta pihak hosting. Hosting yang hanya menyediakan
> PHP 8.1–8.3 atau hanya 8.5 **belum bisa** memakai aplikasi ini — wizard `/install`
> akan menampilkan requirement merah di step pertama, dan itu memang benar.

## 2. Isi paket

```
fsrd-uns-store-<commit>-cpanel.zip
├── fsrd-uns-store/          → upload ke /home/USER/ (SATU LEVEL DI ATAS public_html)
│   ├── app/ bootstrap/ config/ database/ resources/ routes/ storage/
│   ├── public/              (cadangan; yang dipakai adalah public_html/)
│   ├── vendor/              (sudah termasuk — tidak perlu composer install)
│   └── .env.example
├── public_html/             → isi folder ini jadi document root
│   ├── index.php            (sudah dipatch: tahu letak folder aplikasi)
│   ├── _extract.php         (helper sekali-pakai — HAPUS setelah selesai)
│   ├── .htaccess  css/  favicon.ico  robots.txt
└── PANDUAN-HOSTING-CPANEL.md
```

## 3. Upload

Pilih salah satu cara.

### Cara A — ada cPanel / File Manager (paling mudah)
1. Upload `fsrd-uns-store-<commit>-cpanel.zip` ke **home directory** (`/home/USER/`, satu level di atas `public_html`, bukan di dalam `public_html`).
2. Klik kanan → **Extract**.
3. Selesai — folder `fsrd-uns-store/` dan `public_html/` otomatis berada di tempat yang benar. Hapus file zip-nya.

### Cara B — benar-benar hanya FTP (tanpa panel)
1. Lewat FTP, upload `fsrd-uns-store-<commit>-cpanel.zip` **ke dalam** `public_html/`.
   (FTP root biasanya = home directory; masuk ke folder `public_html` dulu.)
2. Masih lewat FTP, upload file `public_html/_extract.php` dari paket ini ke `public_html/`.
3. Buka `https://domain-anda/_extract.php` di browser. Script akan memecah zip ke tempat
   yang benar: `fsrd-uns-store/` ke luar `public_html/`, sisanya ke dalam `public_html/`.
   Tunggu sampai muncul tulisan **Selesai**.
4. **Hapus `_extract.php` dan file `.zip`** dari `public_html/` (penting, demi keamanan).

### Cara C — upload isi folder manual lewat FileZilla
Upload isi `fsrd-uns-store/` ke `/home/USER/fsrd-uns-store/` dan isi `public_html/` ke
`/home/USER/public_html/`. Boleh (~10 ribu file kecil di `vendor/`, sekali jalan tapi lama).

> PENTING: JANGAN upload folder `public/` ke dalam `public_html`. Isi `public_html/` yang
> dipakai adalah salinan `public/` dengan `index.php` versi shared hosting — sudah disiapkan
> di paket. Jangan menimpa `index.php` di `public_html/` dengan file dari `public/index.php`.

## 4. Install lewat wizard (tanpa SSH)

1. Buka `https://domain-anda/` → otomatis diarahkan ke `/install`.
2. **Step Requirement** — pastikan semua hijau. Kalau ada yang merah, minta hosting
   mengaktifkan ekstensi / menaikkan versi PHP dulu.
3. **Step Database** — isi host (`localhost`), port (`3306`), nama database, user, password
   dari pihak hosting. Nama database di cPanel biasanya berawalan nama akun, mis.
   `kampus_fsrd`. Kalau database sudah dibuat dari cPanel, biarkan wizard memakai yang ada.
4. **Step Akun** — nama situs, nama + email + password admin pertama.
5. Klik **Install**. Wizard menjalankan `key:generate`, `migrate`, `storage:link`, membuat
   `.env` di `fsrd-uns-store/.env`, lalu mengunci installer dengan `storage/installed.lock`.

Setelah selesai, login admin di: `https://domain-anda/management-fsrd/masuk`
(URL ini sengaja tidak lazim; jangan disebar).

## 5. Cek setelah install

- [ ] Halaman depan tampil, tidak error 500.
- [ ] Login admin berhasil.
- [ ] Upload 1 gambar produk → gambarnya muncul (uji symlink `/storage`).
      Kalau gambar blank/404, lihat Troubleshooting.

## 6. Troubleshooting

**Error 500 / blank di halaman awal**
- Cek `fsrd-uns-store/storage/logs/laravel.log` (unduh via FTP).
- Pastikan PHP ≥ 8.4.1 dan ekstensi wajib aktif.
- Pastikan `storage/` dan `bootstrap/cache/` bisa ditulis (chmod 755/775). Di cPanel,
  owner file hasil extract biasanya sudah user PHP sendiri, jadi normalnya aman.

**"Folder aplikasi Laravel tidak ditemukan"**
- Folder `fsrd-uns-store/` harus ada **di luar** `public_html`, bersebelahan dengannya
  (bukan di dalam `public_html`). index.php sebenarnya sudah otomatis mencari folder
  bersaudara yang punya `vendor/autoload.php`, jadi pastikan `vendor/` benar-benar ter-upload.

**Gambar upload tidak muncul (404 di `/storage/...`)**
- Idealnya `public_html/storage` adalah symlink ke `fsrd-uns-store/storage/app/public`
  (dibuat otomatis oleh `index.php` saat pertama dibuka). Cek lewat File Manager.
- Kalau hosting memblokir `symlink()`: buat folder `public_html/storage` biasa, lalu minta
  hosting memetakannya, **atau** minta `symlink()` diaktifkan. Opsi cepat tanpa SSH:
  buat file `public_html/link.php` berisi
  `<?php var_dump(symlink(__DIR__.'/../fsrd-uns-store/storage/app/public', __DIR__.'/storage'));`
  buka sekali di browser, lalu hapus.

**Installer tidak muncul / malah 404**
- `storage/installed.lock` mungkin sudah ada (dari instalasi sebelumnya) → hapus file itu
  via FTP, lalu buka `/install` lagi. Sebaliknya, setelah install sukses, jangan hapus
  `installed.lock`, karena situs akan balik diarahkan ke `/install`.

**Ingin mengubah URL/DB setelah install**
- Edit `fsrd-uns-store/.env` langsung lewat FTP (mis. `APP_URL`, kredensial DB), lalu hapus
  cache config kalau ada (`fsrd-uns-store/bootstrap/cache/config.php`).

## 7. Membuat ulang paket ini dari commit yang lebih baru

Dari komputer/server yang punya checkout repo + PHP 8.4/8.5 + Composer:

```bash
git fetch origin
bash scripts/build-shared-hosting.sh origin/main
# hasil: ~/release-build/fsrd-uns-store-<commit>-cpanel.zip
```

`scripts/build-shared-hosting.sh` menjalankan `composer install --no-dev` untuk mengisi
`vendor/`, memecah `public/` ke `public_html/`, dan memasang `index.php` versi shared
hosting dari `scripts/shared-hosting-index.php`. Zip-nya sudah dites: tidak memuat
`Claude.md`/`tests/`, tidak memuat `.env`.
