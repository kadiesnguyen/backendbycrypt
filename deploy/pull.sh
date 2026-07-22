#!/usr/bin/env bash
# bycrypt CMS production deploy (Laravel API + admin-frontend)
# Usage on server: bash /www/wwwroot/cms.bycrypt.net/deploy/pull.sh
set -euo pipefail

ROOT=/www/wwwroot/cms.bycrypt.net
PHP=/www/server/php/82/bin/php
export COMPOSER_ALLOW_SUPERUSER=1

# --- prod public API base for admin UI (baked into Next.js at build) ---
ADMIN_API_URL="${ADMIN_API_URL:-https://cms.bycrypt.net/api/admin}"

cd "$ROOT" && git pull origin main

cd "$ROOT/laravel"
$PHP /usr/local/bin/composer install --no-dev --optimize-autoloader --no-interaction
$PHP artisan migrate --force 2>/dev/null || true
# Loan/KYC uploads use public disk URLs under /storage/* — ensure symlink exists.
$PHP artisan storage:link 2>/dev/null || true
$PHP artisan config:cache && $PHP artisan route:cache

cd "$ROOT/admin-frontend"
# Always rewrite prod env so a stale/missing file cannot fall back to localhost.
printf '%s\n' "NEXT_PUBLIC_ADMIN_API_URL=${ADMIN_API_URL}" > .env.production.local
# Remove local-only env that would override production during build.
rm -f .env.local

if grep -Eiq 'localhost|127\.0\.0\.1' .env.production.local; then
  echo "ERROR: production admin API URL must not be localhost" >&2
  exit 1
fi

npm ci && npm run build

# Fail deploy if bundle still points at localhost
if grep -RIq --include='*.js' 'localhost:8000/api/admin' .next/static .next/server 2>/dev/null; then
  echo "ERROR: built admin bundle still references localhost:8000/api/admin" >&2
  exit 1
fi

pm2 restart bycrypt-cms-admin
curl -sk -o /dev/null -w "api:%{http_code} admin:%{http_code}\n" \
  https://cms.bycrypt.net/api/config https://cms.bycrypt.net/login
