FROM node:22-alpine as frontend-builder
ARG VITE_MAPBOX_TOKEN
WORKDIR /app
COPY package*.json ./
RUN npm install --legacy-peer-deps
COPY . .
RUN VITE_MAPBOX_TOKEN=${VITE_MAPBOX_TOKEN} npm run build

FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    nginx \
    nodejs \ 
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    postgresql-dev \
    icu-dev \
    libwebp-dev \
    libavif-dev \
    zlib-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-avif \
    && docker-php-ext-install -j$(nproc) \
    gd \
    pdo_mysql \
    pdo_pgsql \
    bcmath \
    zip \
    intl \
    exif

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

COPY --from=frontend-builder /app/public/build ./public/build
COPY --from=frontend-builder /app/bootstrap/ssr ./bootstrap/ssr

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/http.d/default.conf

EXPOSE 10000

CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan storage:link && \
    php artisan migrate --force && \
    (node bootstrap/ssr/ssr.js &) && \
    (php artisan queue:work --tries=3 --timeout=90 &) && \
    php-fpm -D && \
    nginx -g 'daemon off;'