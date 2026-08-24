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
# Usage: bash scripts/build-release.sh [ref] [output-dir]
#   ref        default: HEAD (bisa juga mis. origin/main, atau SHA commit)
#   output-dir default: $HOME/release-build

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

(cd "$OUT_DIR/$NAME" && composer install --no-dev --optimize-autoloader --no-interaction)

(cd "$OUT_DIR" && zip -rq "${NAME}.zip" "$NAME" -x "${NAME}/tests/*")

echo "Selesai: ${OUT_DIR}/${NAME}.zip"
