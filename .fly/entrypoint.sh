#!/usr/bin/env sh
set -e

APP_DIR="/var/www/html"
DB_DIR="$APP_DIR/storage/database"
DB_FILE="$DB_DIR/database.sqlite"
SEED_FLAG="$DB_DIR/.seeded"

echo "🚀 Laravel entrypoint started"

# Переконаємось, що директорія існує та має правильні права
mkdir -p "$DB_DIR"
chown -R www-data:www-data "$APP_DIR/storage"

# Створюємо файл бази даних, якщо він фізично відсутній
if [ ! -f "$DB_FILE" ]; then
  echo "📦 SQLite database file not found, creating..."
  touch "$DB_FILE"
  chown www-data:www-data "$DB_FILE"
fi

# 1️⃣ Завжди запускаємо міграції.
# Це безпечно: нові таблиці додадуться, а існуючі не зміняться.
echo "🧱 Running migrations..."
php artisan migrate --force

# 2️⃣ Запускаємо сідери лише один раз, перевіряючи наявність файлу-маркера
if [ ! -f "$SEED_FLAG" ]; then
    echo "🌱 Seeding database for the first time..."
    php artisan db:seed --force

    # Створюємо маркер, щоб наступного разу пропустити цей крок
    touch "$SEED_FLAG"
    chown www-data:www-data "$SEED_FLAG"
else
    echo "✅ Database already seeded, skipping seeders."
fi

# Очищення кешу
php artisan optimize:clear || true

echo "⚡️ Optimizing Laravel..."
php artisan optimize


exec "$@"
