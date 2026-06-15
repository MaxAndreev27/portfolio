#!/bin/sh
set -e

APP_DIR="/app"
DB_FILE="$APP_DIR/storage/database.sqlite"
SEED_FLAG="$APP_DIR/storage/.seeded"

echo "🚀 Starting entrypoint..."

echo "📁 Preparing storage structure..."
# 1️⃣ Підготовка структури папок на Volume
mkdir -p "$APP_DIR/storage/app/public/projects"
mkdir -p "$APP_DIR/storage/framework/cache"
mkdir -p "$APP_DIR/storage/framework/sessions"
mkdir -p "$APP_DIR/storage/framework/views"
mkdir -p "$APP_DIR/storage/logs"

# Права доступу
chown -R www-data:www-data "$APP_DIR/storage"
chmod -R 775 "$APP_DIR/storage"

# 2️⃣ Створюємо базу, якщо її немає
if [ ! -f "$DB_FILE" ]; then
    echo "📦 SQLite database file not found at $DB_FILE, creating..."
    touch "$DB_FILE"
    chown www-data:www-data "$DB_FILE"
fi

# 3️⃣ Налаштування посилань та оптимізація
echo "🔗 Creating storage link..."
rm -f public/storage
php artisan storage:link --force

echo "🧱 Running migrations..."
# Виконуємо міграції для оновлення структури бази на Fly.io
php artisan migrate --force

# 4️⃣ Одноразове заповнення бази (Seeding)
if [ ! -f "$SEED_FLAG" ]; then
    echo "🌱 Seeding database for the first time..."
    php artisan db:seed --force
    touch "$SEED_FLAG"
    chown www-data:www-data "$SEED_FLAG"
else
    echo "✅ Database already seeded, skipping."
fi

# 5️⃣ Фінальна оптимізація перед запуском
# Очищаємо старі кеші та генеруємо нові для максимальної швидкості
echo "⚡️ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache

echo "🏁 Entrypoint finished, starting application..."

# Запускаємо основний процес контейнера (supervisord)
exec "$@"
