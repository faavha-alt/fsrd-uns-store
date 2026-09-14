#!/usr/bin/env bash
set -euo pipefail

# Build paket instalasi MANUAL untuk hosting tanpa SSH & TANPA wizard:
#   - upload semua file lewat FTP
#   - import database/install.sql lewat phpMyAdmin
#   - edit .env (DB + APP_URL)
#   - situs langsung jalan (storage/installed.lock sudah disiapkan di paket)
#
# Usage: bash scripts/build-manual-hosting.sh [ref] [output-dir]
#   ref        default: HEAD
#   output-dir default: <repo>/dist
#
# Env opsional:
#   ADMIN_EMAIL    (default admin@fsrd-uns.local)
#   ADMIN_NAME     (default Administrator)
#   ADMIN_PASSWORD (default acak — dicatat di KREDENSIAL-ADMIN.txt)
#   APP_URL        (default https://ganti-dengan-domain-anda)

REPO_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
REF="${1:-HEAD}"
OUT_DIR="${2:-$REPO_DIR/dist}"
COMMIT="$(git -C "$REPO_DIR" rev-parse --short "$REF")"
NAME="fsrd-uns-store-${COMMIT}-manual"
STAGE="$OUT_DIR/pkg-manual"

ADMIN_EMAIL="${ADMIN_EMAIL:-admin@fsrd-uns.local}"
ADMIN_NAME="${ADMIN_NAME:-Administrator}"
ADMIN_PASSWORD="${ADMIN_PASSWORD:-$(php -r 'echo bin2hex(random_bytes(5));')}"
APP_URL="${APP_URL:-https://ganti-dengan-domain-anda}"

if [ "$REF" = "HEAD" ] && [ -n "$(git -C "$REPO_DIR" status --porcelain --untracked-files=no)" ]; then
    echo "PERINGATAN: ada perubahan belum di-commit — paket dibangun dari HEAD." >&2
fi

for f in scripts/shared-hosting-index.php scripts/shared-hosting-extract.php scripts/generate-install-sql.php PANDUAN-INSTALL-MANUAL.md; do
    [ -f "$REPO_DIR/$f" ] || { echo "ERROR: $f tidak ada." >&2; exit 1; }
done

rm -rf "$STAGE"
mkdir -p "$STAGE/fsrd-uns-store" "$STAGE/public_html"

# 1. Source code tracked-only
git -C "$REPO_DIR" archive "$REF" | tar -x -C "$STAGE/fsrd-uns-store"

# 2. vendor/
(cd "$STAGE/fsrd-uns-store" && composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-req=php >&2)

# 3. install.sql — di-generate dari migration yang ada di paket ini
php "$STAGE/fsrd-uns-store/scripts/generate-install-sql.php" \
    "$STAGE/fsrd-uns-store/database/install.sql" \
    "$ADMIN_EMAIL" "$ADMIN_PASSWORD" "$ADMIN_NAME" >&2

# 4. .env siap pakai (APP_KEY sudah di-generate, kredensial DB masih placeholder)
APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
cat > "$STAGE/fsrd-uns-store/.env" <<ENVEOF
###  FILE INI WAJIB DIEDIT SEBELUM SITUS DIBUKA  ###
###  Yang harus diganti: APP_URL, DB_DATABASE, DB_USERNAME, DB_PASSWORD  ###
###  (APP_KEY sudah dibuatkan — JANGAN diubah/dikosongkan)  ###

APP_NAME="FSRD UNS Store"
APP_ENV=production
APP_KEY=${APP_KEY}
APP_DEBUG=false
APP_URL=${APP_URL}

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ganti_dengan_nama_database
DB_USERNAME=ganti_dengan_user_database
DB_PASSWORD=ganti_dengan_password_database

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@ganti-dengan-domain-anda"
MAIL_FROM_NAME="\${APP_NAME}"
ENVEOF

# 5. installed.lock — supaya middleware EnsureInstalled tidak me-redirect ke /install
#    (wizard tidak dipakai; skema database sudah diimport dari install.sql)
touch "$STAGE/fsrd-uns-store/storage/installed.lock"

# 6. Document root
cp -a "$STAGE/fsrd-uns-store/public/." "$STAGE/public_html/"
cp "$REPO_DIR/scripts/shared-hosting-index.php" "$STAGE/public_html/index.php"
cp "$REPO_DIR/scripts/shared-hosting-extract.php" "$STAGE/public_html/_extract.php"

# 7. Panduan + kredensial admin
cp "$REPO_DIR/PANDUAN-INSTALL-MANUAL.md" "$STAGE/PANDUAN-INSTALL-MANUAL.md"
cat > "$STAGE/KREDENSIAL-ADMIN.txt" <<CREDEOF
Kredensial admin fsrd-uns-store (dibuat otomatis oleh scripts/build-manual-hosting.sh)

  URL login : <APP_URL>/management-fsrd/masuk
  Email     : ${ADMIN_EMAIL}
  Password  : ${ADMIN_PASSWORD}

Password ini tersimpan sebagai hash bcrypt di database (import install.sql).
Segera ganti setelah login pertama lewat menu User di admin panel,
lalu HAPUS file ini dari server.
CREDEOF

# 8. Zip (dua folder di root supaya extract di /home/USER langsung pas)
ZIP="$OUT_DIR/${NAME}.zip"
rm -f "$ZIP"
(cd "$STAGE" && zip -rq "$ZIP" fsrd-uns-store public_html PANDUAN-INSTALL-MANUAL.md KREDENSIAL-ADMIN.txt)

# 9. Verifikasi isi paket
LIST="$(unzip -l "$ZIP")"
fail() { echo "ERROR: $1" >&2; exit 1; }
grep -q "fsrd-uns-store/vendor/autoload.php" <<<"$LIST" || fail "vendor/ tidak masuk paket"
grep -q "fsrd-uns-store/database/install.sql" <<<"$LIST" || fail "install.sql tidak masuk paket"
grep -q "fsrd-uns-store/\.env$" <<<"$LIST" || fail ".env tidak masuk paket"
grep -q "fsrd-uns-store/storage/installed.lock" <<<"$LIST" || fail "installed.lock tidak masuk paket"
grep -qiE '(^| )fsrd-uns-store/Claude\.md$' <<<"$LIST" && fail "Claude.md ikut ke paket"

echo "Selesai: $ZIP ($(du -h "$ZIP" | cut -f1))"
echo "Admin   : ${ADMIN_EMAIL} / ${ADMIN_PASSWORD}"
echo "APP_URL : ${APP_URL}"
