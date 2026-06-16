# syntax = docker/dockerfile:experimental

ARG PHP_VERSION=8.4
ARG NODE_VERSION=24
FROM ubuntu:24.04 as base
LABEL fly_launch_runtime="laravel"

ARG PHP_VERSION
ENV DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/composer \
    COMPOSER_MAX_PARALLEL_HTTP=24 \
    PHP_PM_MAX_CHILDREN=10 \
    PHP_PM_START_SERVERS=3 \
    PHP_MIN_SPARE_SERVERS=2 \
    PHP_MAX_SPARE_SERVERS=4 \
    PHP_DATE_TIMEZONE=UTC \
    PHP_DISPLAY_ERRORS=Off \
    PHP_ERROR_REPORTING=22527 \
    PHP_MEMORY_LIMIT=256M \
    PHP_MAX_EXECUTION_TIME=90 \
    PHP_POST_MAX_SIZE=100M \
    PHP_UPLOAD_MAX_FILE_SIZE=100M \
    PHP_ALLOW_URL_FOPEN=Off

# 1. Install PHP, Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY .fly/php/ondrej_ubuntu_php.gpg /etc/apt/trusted.gpg.d/ondrej_ubuntu_php.gpg
ADD .fly/php/packages/${PHP_VERSION}.txt /tmp/php-packages.txt

RUN apt-get update \
    && apt-get install -y --no-install-recommends gnupg2 ca-certificates git-core curl zip unzip \
    rsync vim-tiny htop sqlite3 nginx supervisor cron \
    && ln -sf /usr/bin/vim.tiny /etc/alternatives/vim \
    && ln -sf /etc/alternatives/vim /usr/bin/vim \
    && echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu noble main" > /etc/apt/sources.list.d/ondrej-ubuntu-php-noble.list \
    && apt-get update \
    && apt-get -y --no-install-recommends install $(cat /tmp/php-packages.txt) \
    && ln -sf /usr/sbin/php-fpm${PHP_VERSION} /usr/sbin/php-fpm \
    && mkdir -p /var/www/html/public && echo "index" > /var/www/html/public/index.php \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# 2. Copy config files to proper locations
COPY .fly/nginx/ /etc/nginx/
COPY .fly/fpm/ /etc/php/${PHP_VERSION}/fpm/
COPY .fly/supervisor/ /etc/supervisor/
COPY .fly/entrypoint.sh /entrypoint
COPY .fly/start-nginx.sh /usr/local/bin/start-nginx
RUN chmod +x /usr/local/bin/start-nginx
RUN chmod +x /entrypoint

# 3. Copy application code
COPY . /var/www/html
WORKDIR /var/www/html

# 4. Setup application dependencies
RUN composer install --optimize-autoloader --no-dev \
    && mkdir -p storage/logs \
    && chown -R www-data:www-data /var/www/html \
    && echo "MAILTO=\"\"\n* * * * * www-data /usr/bin/php /var/www/html/artisan schedule:run" > /etc/cron.d/laravel \
    && sed -i '/->withMiddleware(function (Middleware \$middleware) {/a \    $middleware->trustProxies(at: "*");' bootstrap/app.php


# 🚀 Збірка статики: використовуємо рідний base образ і просто додаємо Node.js
FROM base as node_modules_go_brrr

ARG NODE_VERSION

# Встановлюємо Node.js прямо в наше Ubuntu-середовище через офіційний скрипт Nodesource
RUN curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION}.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# PHP, код та vendor вже на місці, бо ми наслідуємося від stage 'base'!
RUN php artisan wayfinder:generate --with-form

# Передача ARG для Vite
ARG VITE_APP_NAME
ARG VITE_REVERB_APP_KEY
ARG VITE_REVERB_HOST
ARG VITE_REVERB_PORT
ARG VITE_REVERB_SCHEME

ENV VITE_APP_NAME=$VITE_APP_NAME \
    VITE_REVERB_APP_KEY=$VITE_REVERB_APP_KEY \
    VITE_REVERB_HOST=$VITE_REVERB_HOST \
    VITE_REVERB_PORT=$VITE_REVERB_PORT \
    VITE_REVERB_SCHEME=$VITE_REVERB_SCHEME

# Компіляція фронтенду
RUN if [ -f "vite.config.js" ] || [ -f "vite.config.ts" ]; then \
    ASSET_CMD="build"; \
    else \
    ASSET_CMD="production"; \
    fi; \
    if [ -f "yarn.lock" ]; then \
    yarn install --frozen-lockfile; \
    VITE_WAYFINDER=false yarn $ASSET_CMD; \
    elif [ -f "pnpm-lock.yaml" ]; then \
    corepack enable && corepack prepare pnpm@latest-8 --activate; \
    pnpm install --frozen-lockfile; \
    VITE_WAYFINDER=false pnpm run $ASSET_CMD; \
    elif [ -f "package-lock.json" ]; then \
    npm ci --no-audit; \
    VITE_WAYFINDER=false npm run $ASSET_CMD; \
    else \
    npm install; \
    VITE_WAYFINDER=false npm run $ASSET_CMD; \
    fi;


# 🏁 Фінальний образ
FROM base

# Забираємо скомпільовану статику
COPY --from=node_modules_go_brrr /var/www/html/public /var/www/html/public-npm
RUN rsync -ar /var/www/html/public-npm/ /var/www/html/public/ \
    && rm -rf /var/www/html/public-npm

# Оптимізація Laravel
RUN php artisan view:cache
RUN php artisan icons:cache
RUN php artisan filament:optimize

RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080
ENTRYPOINT ["/entrypoint"]