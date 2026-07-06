# syntax=docker/dockerfile:1.7

# ----------------------------------------------------------------------------
# TryPost Dockerfile — multi-stage with `dev` and `production` targets.
#
#   docker build --target dev        -t trypost:dev  -f docker/Dockerfile .
#   docker build --target production -t trypost:prod -f docker/Dockerfile .
# ----------------------------------------------------------------------------

ARG PHP_VERSION=8.4
ARG NODE_VERSION=22

# ----------------------------------------------------------------------------
# Stage 1: system-base — PHP-FPM + system packages + extensions
# ----------------------------------------------------------------------------
FROM php:${PHP_VERSION}-fpm-alpine AS system-base

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
        $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-avif \
    && docker-php-ext-install -j"$(nproc)" \
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
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS \
    && rm -rf /tmp/* /var/cache/apk/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ----------------------------------------------------------------------------
# Stage 2: composer-deps — full PHP deps (incl. dev) for tooling/asset build
# ----------------------------------------------------------------------------
FROM system-base AS composer-deps

COPY composer.json composer.lock ./
RUN composer install \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-interaction

# ----------------------------------------------------------------------------
# Stage 3: composer-deps-prod — production deps only (no dev tooling)
# ----------------------------------------------------------------------------
FROM system-base AS composer-deps-prod

COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-interaction

# ----------------------------------------------------------------------------
# Stage 4: asset-build — wayfinder TS gen + Vite + Inertia SSR build
#
# PHP and Node together in one stage because @laravel/vite-plugin-wayfinder
# invokes `php artisan wayfinder:generate` during vite build — splitting
# the stages would mean the vite phase has no PHP available.
#
# Vite inlines VITE_* values into the JS bundle at build time. Pass them
# as build args (--build-arg VITE_REVERB_APP_KEY=...) so the bundle reaches
# the browser with the correct Reverb/PostHog config. Runtime env vars on
# the container have no effect on the already-compiled bundle.
# ----------------------------------------------------------------------------
FROM composer-deps AS asset-build

# Node 22 for Vite + npm scripts.
RUN apk add --no-cache nodejs npm

ARG VITE_APP_NAME=TryPost
ARG VITE_REVERB_APP_KEY=
ARG VITE_REVERB_HOST=localhost
ARG VITE_REVERB_PORT=8080
ARG VITE_REVERB_SCHEME=http
ARG VITE_POSTHOG_ENABLED=false
ARG VITE_POSTHOG_API_KEY=
ARG VITE_POSTHOG_HOST=https://us.i.posthog.com

# Stub PHP envs so artisan can boot (overridden at runtime).
ENV APP_KEY=base64:c3R1Yi13YXlmaW5kZXItZ2VuLWtleS1mb3ItYnVpbGRpbmctYXNzZXRzMA== \
    APP_ENV=production \
    APP_DEBUG=false \
    APP_URL=http://localhost \
    VITE_APP_NAME=${VITE_APP_NAME} \
    VITE_REVERB_APP_KEY=${VITE_REVERB_APP_KEY} \
    VITE_REVERB_HOST=${VITE_REVERB_HOST} \
    VITE_REVERB_PORT=${VITE_REVERB_PORT} \
    VITE_REVERB_SCHEME=${VITE_REVERB_SCHEME} \
    VITE_POSTHOG_ENABLED=${VITE_POSTHOG_ENABLED} \
    VITE_POSTHOG_API_KEY=${VITE_POSTHOG_API_KEY} \
    VITE_POSTHOG_HOST=${VITE_POSTHOG_HOST}

COPY --from=composer-deps /var/www/html/vendor ./vendor
COPY . .

# Ensure Laravel runtime dirs exist
RUN mkdir -p storage/framework/cache/data \
             storage/framework/sessions \
             storage/framework/views \
             storage/logs \
             bootstrap/cache

# ----- FIX: Remove Pail provider -----
RUN sed -i '/Laravel\\Pail\\PailServiceProvider/d' bootstrap/providers.php || true

RUN composer dump-autoload --no-scripts --optimize \
    && php artisan wayfinder:generate --with-form \
    && npm ci --no-audit --no-fund \
    && npm run build \
    && npm run build:ssr
