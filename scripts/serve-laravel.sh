#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT_DIR/laravel"

docker run --rm --network backend_default \
  -v "$ROOT_DIR/laravel:/app" -w /app \
  -p 8000:8000 \
  php:8.2-cli bash -c "\
    apt-get update -qq && apt-get install -y -qq libzip-dev libpng-dev >/dev/null && \
    docker-php-ext-install pdo_mysql gd zip >/dev/null 2>&1 && \
    php artisan serve --host=0.0.0.0 --port=8000"
