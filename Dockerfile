FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    curl \
    git \
    unzip \
    shadow \
    tzdata \
    postgresql-client \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    libavif-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    linux-headers \
    autoconf \
    dpkg-dev dpkg \
    file \
    g++ \
    gcc \
    libc-dev \
    make \
    pkgconf \
    re2c \
    nodejs \
    npm \
    netcat-openbsd

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-avif \
    && docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    pgsql \
    gd \
    zip \
    opcache \
    bcmath \
    exif \
    pcntl \
    intl \
    sockets \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY . .

# Install dependencies
RUN composer install --no-scripts --no-autoloader --prefer-dist --no-interaction

# REMOVE PAIL PROVIDER BEFORE DUMP-AUTOLOAD
RUN sed -i '/Laravel\\Pail\\PailServiceProvider/d' bootstrap/providers.php || true

# Generate autoloader
RUN composer dump-autoload --optimize --classmap-authoritative --no-scripts

# Build frontend assets
RUN npm ci --no-audit --no-fund \
    && npm run build

# Set permissions
RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy config files
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.prod.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.prod.ini /usr/local/etc/php/conf.d/99-trypost.ini

# Copy entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
