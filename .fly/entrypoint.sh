#!/usr/bin/env sh
set -e

# Визначаємо шлях до застосунку та бази даних згідно з fly.toml
APP_DIR="/var/www/html"
DB_DIR="$APP_DIR/storage/database"
DB_FILE="$DB_DIR/database.sqlite"
SEED_FLAG="$APP_DIR/storage/.seeded"

echo "🚀 Laravel entrypoint started"

# 1️⃣ Підготовка структури папок на Volume
# Оскільки Volume порожній при першому запуску, створюємо необхідні директорії
echo "📁 Preparing storage structure..."
mkdir -p "$DB_DIR"
mkdir -p "$APP_DIR/storage/app/public/projects"
mkdir -p "$APP_DIR/storage/framework/cache"
mkdir -p "$APP_DIR/storage/framework/sessions"
mkdir -p "$APP_DIR/storage/framework/views"
mkdir -p "$APP_DIR/storage/logs"

# Встановлюємо права власності для www-data (користувач PHP-FPM та Nginx)
# Це важливо для PHP 8.4 та Ubuntu 22.04 [cite: 1, 2]
chown -R www-data:www-data "$APP_DIR/storage"
chmod -R 775 "$APP_DIR/storage"

# 2️⃣ Робота з базою даних SQLite
if [ ! -f "$DB_FILE" ]; then
    echo "📦 SQLite database file not found at $DB_FILE, creating..."
    touch "$DB_FILE"
    chown www-data:www-data "$DB_FILE"
fi

# 3️⃣ Налаштування посилань та оптимізація
echo "🔗 Creating storage link..."
# Створює публічне посилання для доступу до зображень
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
php artisan optimize:clear || true
php artisan optimize
php artisan filament:optimize || true

echo "🏁 Entrypoint finished, starting application..."

# Запускаємо основний процес контейнера (supervisord)
exec "$@"
