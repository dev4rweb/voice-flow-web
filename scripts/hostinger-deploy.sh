#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

echo "[deploy] Working directory: $ROOT"

if [[ ! -f .env ]]; then
    echo "[deploy] ERROR: .env not found. Create it on the server before the first deploy."
    exit 1
fi

if command -v composer >/dev/null 2>&1; then
    echo "[deploy] composer install"
    composer install --no-dev --optimize-autoloader --no-interaction
else
    echo "[deploy] WARNING: composer not found in PATH"
fi

if command -v npm >/dev/null 2>&1; then
    echo "[deploy] npm ci && npm run build"
    npm ci --no-audit --no-fund
    npm run build
else
    echo "[deploy] WARNING: npm not found — run 'npm run build' locally and upload public/build if assets are missing"
fi

mkdir -p storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache 2>/dev/null || true

if [[ ! -L public/storage ]]; then
    php artisan storage:link --force 2>/dev/null || true
fi

echo "[deploy] artisan migrate & cache"
php artisan migrate --force --no-interaction
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction

echo "[deploy] Done."
