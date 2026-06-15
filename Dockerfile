# Використовуємо офіційний образ FrankenPHP з PHP 8.4
FROM dunglas/frankenphp:1-php8.4

# Встановлюємо PHP-розширення та Composer
RUN install-php-extensions pcntl opcache gd zip intl pdo_sqlite mbstring xml @composer

# Встановлюємо системні залежності (git для Composer, nodejs/npm для збірки фронтенду)
RUN apt-get update && apt-get install -y \
    git \
    supervisor \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Встановлюємо робочу директорію
WORKDIR /app

# 1. Спочатку копіюємо тільки package.json для ефективного кешування шарів Docker
COPY package*.json ./
RUN npm install

# 2. Копіюємо конфігурацію Composer та встановлюємо PHP залежності
COPY composer.* ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# 3. Копіюємо весь інший код проекту
COPY . .

# 4. Генеруємо автозавантажувач та оптимізуємо
RUN composer dump-autoload --optimize

# 5. Вимикаємо Wayfinder для збірки в Docker, якщо він викликає помилки шляхів
ENV VITE_WAYFINDER=false
RUN npm run build

# Встановлюємо права доступу для веб-користувача
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Налаштування Supervisor (копіювання конфігів)
RUN mkdir -p /etc/supervisor/conf.d/
COPY .fly/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY .fly/supervisor/conf.d/ /etc/supervisor/conf.d/

# Робимо скрипт entrypoint.sh виконуваним
RUN chmod +x /app/entrypoint.sh

# Запуск
ENTRYPOINT ["/bin/sh", "/app/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]