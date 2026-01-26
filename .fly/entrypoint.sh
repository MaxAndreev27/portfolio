#!/usr/bin/env sh
set -e

APP_DIR="/var/www/html"
DB_DIR="$APP_DIR/storage/database"
DB_FILE="$DB_DIR/database.sqlite"

cd $APP_DIR

echo "🔍 Checking SQLite database..."

# 1. Створюємо папку volume (на всякий)
mkdir -p "$DB_DIR"

# 2. Якщо БД НЕ існує → ініціалізуємо
if [ ! -f "$DB_FILE" ]; then
    echo "🗄️ SQLite database not found. Initializing..."

    touch "$DB_FILE"
    chown -R www-data:www-data "$APP_DIR/storage"

    php artisan key:generate --force
    php artisan migrate --force --seed
else
    echo "✅ SQLite database exists. Skipping migrations."
fi

# 3. Очистка кешів (БЕЗ optimize:clear)
php artisan config:clear
php artisan route:clear
php artisan view:clear

exec supervisord -c /etc/supervisor/supervisord.conf
