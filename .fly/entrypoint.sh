#!/usr/bin/env sh
set -e

DB_FILE=/var/www/html/database/database.sqlite

# 1️⃣ Створюємо файл бази та виконуємо міграції лише якщо його нема
if [ ! -f "$DB_FILE" ]; then
  echo "SQLite database not found. Creating database and running migrations..."
  mkdir -p /var/www/html/database
  touch "$DB_FILE"

  # Генеруємо ключ Laravel, якщо його ще нема
  php artisan key:generate || true

  # Виконуємо міграції та seed
  php artisan migrate --force --seed

  # Очищуємо кеш
  php artisan optimize:clear
else
  echo "SQLite database already exists. Skipping migrations."
fi

# 2️⃣ Виконуємо user scripts, якщо вони є
if [ -d /var/www/html/.fly/scripts ]; then
  for f in /var/www/html/.fly/scripts/*.sh; do
    [ -f "$f" ] && bash "$f" -e
  done
fi

# 3️⃣ Запускаємо передану команду або supervisord
if [ $# -gt 0 ]; then
    exec "$@"
else
    exec supervisord -c /etc/supervisor/supervisord.conf
fi
