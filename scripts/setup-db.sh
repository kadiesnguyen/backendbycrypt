#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
DUMP_FILE="$ROOT_DIR/database/wnskcexcom.sql.gz"

cd "$ROOT_DIR"

if [[ ! -f "$DUMP_FILE" ]]; then
  echo "Missing dump: $DUMP_FILE"
  exit 1
fi

echo "Starting MySQL container..."
docker compose up -d mysql

echo "Waiting for MySQL to be ready..."
for i in $(seq 1 60); do
  if docker compose exec -T mysql mysqladmin ping -h 127.0.0.1 -uroot -proot_local_dev --silent 2>/dev/null; then
    break
  fi
  sleep 2
  if [[ "$i" -eq 60 ]]; then
    echo "MySQL did not become ready in time."
    exit 1
  fi
done

echo "Importing database dump..."
zcat "$DUMP_FILE" | docker compose exec -T mysql mysql -uwnskcexcom -pwnskcex_local_dev wnskcexcom

echo "Verifying import..."
docker compose exec -T mysql mysql -uwnskcexcom -pwnskcex_local_dev wnskcexcom -e \
  "SELECT COUNT(*) AS table_count FROM information_schema.tables WHERE table_schema='wnskcexcom'; SELECT COUNT(*) AS user_count FROM tw_user;"

echo "Local database ready on 127.0.0.1:3308 (db=wnskcexcom, user=wnskcexcom)"
