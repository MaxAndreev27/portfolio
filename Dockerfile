# Використовуємо офіційний образ FrankenPHP з PHP 8.4
FROM dunglas/frankenphp:1-php8.4

# Встановлюємо PHP-розширення та Composer
RUN install-php-extensions pcntl opcache gd zip intl pdo_sqlite mbstring xml @composer

# Встановлюємо системні залежності
RUN apt-get update && apt-get install -y git supervisor && rm -rf /var/lib/apt/lists/*

# Створюємо директорію для конфігів Supervisor
RUN mkdir -p /etc/supervisor/conf.d/

# Встановлюємо робочу директорію
WORKDIR /app

# Встановлення Node.js та збірка
RUN apt-get update && apt-get install -y nodejs npm
COPY package*.json ./
RUN npm install
COPY . .
ENV VITE_WAYFINDER=false
RUN npm run build

# Копіюємо проект
COPY . /app

# Встановлюємо залежності Composer
RUN composer install --no-dev --optimize-autoloader

# Копіюємо конфігурацію Supervisor
COPY .fly/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY .fly/supervisor/conf.d/ /etc/supervisor/conf.d/

# Робимо скрипт entrypoint.sh виконуваним
RUN chmod +x /app/entrypoint.sh

# Встановлюємо права доступу для веб-користувача
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Очищуємо права і встановлюємо їх заново для всіх
COPY entrypoint.sh /app/entrypoint.sh
RUN chmod 755 /app/entrypoint.sh

# Встановлюємо права доступу для веб-користувача
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# ЗАПУСК: Спочатку виконуємо entrypoint, потім запускаємо supervisord
ENTRYPOINT ["/bin/sh", "/app/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]