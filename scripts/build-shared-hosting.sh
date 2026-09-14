#!/usr/bin/env bash
set -euo pipefail

# Build paket rilis khusus HOSTING TANPA SSH (FTP + DB saja), document root
# terkunci di public_html/ — layout cPanel standar:
#
#   fsrd-uns-store/   <- folder aplikasi (upload ke /home/USER/, DI LUAR public_html)
#   public_html/      <- document root (isi folder public/ + index.php yang sudah dipatch)
#   PANDUAN-HOSTING-CPANEL.md
#
# Berbeda dari scripts/build-release.sh (yang menghasilkan SATU folder + butuh
# document root diarahkan ke public/), paket ini sudah dipecah dua dan
# index.php-nya sudah tahu di mana folder aplikasi berada.
#
# Usage: bash scripts/build-shared-hosting.sh [ref] [output-dir]
#   ref        default: HEAD (bisa origin/main / SHA)
#   output-dir default: $HOME/release-build

REPO_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
REF="${1:-HEAD}"
OUT_DIR="${2:-$HOME/release-build}"
COMMIT="$(git -C "$REPO_DIR" rev-parse --short "$REF")"
NAME="fsrd-uns-store-${COMMIT}-cpanel"
STAGE="$OUT_DIR/pkg"

if [ "$REF" = "HEAD" ] && [ -n "$(git -C "$REPO_DIR" status --porcelain)" ]; then
    echo "PERINGATAN: working tree ada perubahan belum di-commit — paket dibangun dari HEAD (commit terakhir)." >&2
fi

for f in scripts/shared-hosting-index.php scripts/shared-hosting-extract.php PANDUAN-HOSTING-CPANEL.md; do
    [ -f "$REPO_DIR/$f" ] || { echo "ERROR: $f tidak ada." >&2; exit 1; }
done

rm -rf "$OUT_DIR/pkg"
mkdir -p "$STAGE/fsrd-uns-store" "$STAGE/public_html"

# 1. Source code tracked-only (tanpa .env, isi storage asli, .git, Claude.md, tests/)
git -C "$REPO_DIR" archive "$REF" | tar -x -C "$STAGE/fsrd-uns-store"

# 2. vendor/ — hosting tanpa SSH tidak bisa `composer install`.
#    Target server: PHP 8.3/8.4 (composer.json sudah memakai config.platform.php
#    8.3.0, jadi lock-nya di-resolve untuk Symfony 7.4; phpspreadsheet 1.30.x
#    masih mengunci <8.5). Kalau build dijalankan memakai PHP 8.5+ (kasus mesin
#    dev ini), composer menolak lock hanya karena batas atas metadata
#    phpspreadsheet — versinya toh sudah dipatok lock, jadi dipakai
#    --ignore-platform-req=php. composer.json TIDAK diubah.
(cd "$STAGE/fsrd-uns-store" && composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-req=php)

# 3. Document root: isi public/ + index.php versi shared hosting + helper extract
cp -a "$STAGE/fsrd-uns-store/public/." "$STAGE/public_html/"
cp "$REPO_DIR/scripts/shared-hosting-index.php" "$STAGE/public_html/index.php"
cp "$REPO_DIR/scripts/shared-hosting-extract.php" "$STAGE/public_html/_extract.php"

# 4. Panduan ikut ke dalam paket
cp "$REPO_DIR/PANDUAN-HOSTING-CPANEL.md" "$STAGE/PANDUAN-HOSTING-CPANEL.md"

# 5. Zip — dua folder di root, jadi extract di /home/USER langsung pas layout-nya
rm -f "$OUT_DIR/${NAME}.zip"
(cd "$STAGE" && zip -rq "$OUT_DIR/${NAME}.zip" fsrd-uns-store public_html PANDUAN-HOSTING-CPANEL.md)

# 6. Verifikasi cepat isi paket
unzip -l "$OUT_DIR/${NAME}.zip" >/dev/null || { echo "ERROR: zip tidak valid." >&2; exit 1; }
unzip -l "$OUT_DIR/${NAME}.zip" | grep -q "fsrd-uns-store/vendor/autoload.php" || { echo "ERROR: vendor/ tidak masuk paket." >&2; exit 1; }
if unzip -l "$OUT_DIR/${NAME}.zip" | grep -qi "claude.md"; then echo "ERROR: Claude.md ikut ke paket." >&2; exit 1; fi

echo "Selesai: $OUT_DIR/${NAME}.zip ($(du -h "$OUT_DIR/${NAME}.zip" | cut -f1))"
