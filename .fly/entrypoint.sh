#!/usr/bin/env sh
set -e

APP_DIR="/var/www/html"
DB_DIR="$APP_DIR/storage/database"
DB_FILE="$DB_DIR/database.sqlite"

echo "🚀 Laravel entrypoint started"

# ensure dirs exist
mkdir -p "$DB_DIR"
chown -R www-data:www-data "$APP_DIR/storage"

# if DB does not exist → first boot
if [ ! -f "$DB_FILE" ]; then
  echo "📦 SQLite database not found, creating..."
  touch "$DB_FILE"
  chown www-data:www-data "$DB_FILE"

  echo "🧱 Running migrations & seeders..."
  php artisan migrate --force --seed
else
  echo "✅ SQLite database already exists, skipping migrations"
fi

# clear cache safely (now DB exists)
php artisan optimize:clear || true

exec "$@"
