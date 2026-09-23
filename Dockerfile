FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    nginx \
    nodejs \
    npm \
    && docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    bcmath \
    intl \
    zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

RUN npm install
RUN npm run build

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

RUN printf '%s\n' \
'server {' \
'    listen 10000;' \
'    listen [::]:10000;' \
'    root /var/www/public;' \
'    index index.php index.html;' \
'' \
'    location / {' \
'        try_files $uri $uri/ /index.php?$query_string;' \
'    }' \
'' \
'    location ~ \.php$ {' \
'        include fastcgi_params;' \
'        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' \
'        fastcgi_param PATH_INFO $fastcgi_path_info;' \
'        fastcgi_pass 127.0.0.1:9000;' \
'    }' \
'' \
'    location ~ /\.ht {' \
'        deny all;' \
'    }' \
'}' \
> /etc/nginx/sites-available/default

RUN printf '%s\n' \
'#!/bin/sh' \
'set -e' \
'php artisan migrate --force' \
'php-fpm -D' \
'exec nginx -g "daemon off;"' \
> /start.sh

RUN chmod +x /start.sh

EXPOSE 10000

CMD ["sh", "-c", "php artisan migrate --force && exec /start.sh"]
