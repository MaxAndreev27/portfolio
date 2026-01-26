#!/usr/bin/env sh
set -e

DB_DIR="/var/www/html/storage/database"
DB_FILE="$DB_DIR/database.sqlite"

echo "🔎 Checking SQLite database..."

# 1. Створюємо директорію (volume вже змонтований сюди)
mkdir -p "$DB_DIR"

# 2. Якщо БД ще нема — ініціалізуємо
if [ ! -f "$DB_FILE" ]; then
  echo "🆕 SQLite database not found. Initializing..."

  touch "$DB_FILE"
  chown -R www-data:www-data "$DB_DIR"

  echo "🚀 Running migrations..."
  php artisan migrate --force

  echo "🌱 Seeding database..."
  php artisan db:seed --force

else
  echo "✅ SQLite database already exists. Skipping migrations."
fi

# 3. Запуск користувацьких скриптів (як у тебе було)
if [ -d /var/www/html/.fly/scripts ]; then
  for f in /var/www/html/.fly/scripts/*.sh; do
    echo "▶ Running $f"
    bash "$f"
  done
fi

# 4. Старт основного процесу
exec supervisord -c /etc/supervisor/supervisord.conf
