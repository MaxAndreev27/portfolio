#!/usr/bin/env sh
set -e

# Шлях до бази на МОНТОВАНОМУ диску
DB_FILE=/var/www/html/storage/database/database.sqlite

# 1️⃣ Створюємо файл бази та виконуємо міграції лише якщо його нема
if [ ! -f "$DB_FILE" ]; then
  echo "SQLite database not found on volume. Creating..."
  touch "$DB_FILE"

  # Встановлюємо права, щоб PHP міг писати у файл
  chown www-data:www-data "$DB_FILE"
  chown www-data:www-data /var/www/html/storage/database

  echo "Running migrations and seed..."
  php artisan migrate --force --seed
  php artisan optimize:clear
else
  echo "SQLite database found. Skipping initial setup."
  # Про всяк випадок запускаємо міграції (без seed),
  # щоб оновити структуру при деплої нового коду
  php artisan migrate --force
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
