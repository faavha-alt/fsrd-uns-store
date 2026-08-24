#!/usr/bin/env bash
set -euo pipefail

# Build sebuah zip rilis (source code + vendor/) dari sebuah git ref (default
# HEAD), untuk dipakai sebagai "master zip" instalasi server baru lewat wizard
# /install.
#
# Jalankan ini DARI server/checkout yang punya akses ke ref yang mau
# dipaketkan (lokal HEAD, atau origin/main setelah `git fetch`) — biasanya
# produksi, karena PHP di situ cocok untuk build vendor/. TIDAK PERNAH
# menyentuh/checkout working tree aslinya (semua kerja terjadi di direktori
# sementara terpisah lewat `git archive <ref>`, tanpa `git checkout`/`pull`/
# `reset` apa pun) — aman dijalankan di produksi yang sedang live sekalipun,
# walau ref yang di-build beda dari yang sedang live. Otomatis TIDAK
# menyertakan .env atau isi storage/ yang nyata (data upload/order
# pelanggan) — hanya file yang di-track git + vendor/ hasil composer install
# bersih.
#
# Repo ini PUBLIC di GitHub — .env.example sengaja TIDAK pernah menyimpan
# LICENSE_SERVER_URL/LICENSE_API_SECRET yang asli (kalau di-commit, siapa
# saja bisa baca secret-nya). Nilai asli disuntikkan HANYA ke paket zip hasil
# build ini, lewat env var LICENSE_SERVER_URL & LICENSE_API_SECRET saat
# menjalankan script ini — TIDAK PERNAH ditulis ke git.
#
# Usage: bash scripts/build-release.sh [ref] [output-dir]
#   ref        default: HEAD (bisa juga mis. origin/main, atau SHA commit)
#   output-dir default: $HOME/release-build
#
# Supaya paket rilis yang dihasilkan benar-benar bisa dipakai orang lain
# untuk instalasi (bukan berhenti di step Lisensi wizard /install), set:
#   LICENSE_SERVER_URL=https://lisensi.mipa.uns.ac.id \
#   LICENSE_API_SECRET=<secret asli> \
#   bash scripts/build-release.sh origin/main

REPO_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
REF="${1:-HEAD}"
OUT_DIR="${2:-$HOME/release-build}"
COMMIT="$(git -C "$REPO_DIR" rev-parse --short "$REF")"
NAME="fsrd-uns-store-${COMMIT}"

if [ "$REF" = "HEAD" ] && [ -n "$(git -C "$REPO_DIR" status --porcelain)" ]; then
    echo "PERINGATAN: working tree ada perubahan belum di-commit — zip akan pakai HEAD (commit terakhir), BUKAN perubahan yang belum di-commit itu." >&2
fi

rm -rf "$OUT_DIR"
mkdir -p "$OUT_DIR/$NAME"

git -C "$REPO_DIR" archive "$REF" | tar -x -C "$OUT_DIR/$NAME"

ENV_EXAMPLE="$OUT_DIR/$NAME/.env.example"
if [ -n "${LICENSE_SERVER_URL:-}" ] && [ -n "${LICENSE_API_SECRET:-}" ] && [ -f "$ENV_EXAMPLE" ]; then
    sed -i "s|^# LICENSE_SERVER_URL=.*|LICENSE_SERVER_URL=${LICENSE_SERVER_URL}|" "$ENV_EXAMPLE"
    sed -i "s|^# LICENSE_API_SECRET=.*|LICENSE_API_SECRET=${LICENSE_API_SECRET}|" "$ENV_EXAMPLE"
    sed -i "s|^# LICENSE_GRACE_DAYS=.*|LICENSE_GRACE_DAYS=3|" "$ENV_EXAMPLE"
    echo "Konfigurasi license server disuntikkan ke paket rilis ini." >&2
else
    echo "PERINGATAN: LICENSE_SERVER_URL/LICENSE_API_SECRET tidak di-set — paket rilis ini TIDAK akan bisa menyelesaikan instalasi (wizard /install akan berhenti di step Lisensi dengan pesan 'paket ini belum dikonfigurasi'). Ini SENGAJA aman-secara-default, bukan bug — set kedua env var itu kalau memang mau bikin paket yang bisa dipakai end-user." >&2
fi

(cd "$OUT_DIR/$NAME" && composer install --no-dev --optimize-autoloader --no-interaction)

(cd "$OUT_DIR" && zip -rq "${NAME}.zip" "$NAME" -x "${NAME}/tests/*")

echo "Selesai: ${OUT_DIR}/${NAME}.zip"
