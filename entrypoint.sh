#!/bin/sh

# Wait for PostgreSQL
echo "Waiting for PostgreSQL at $DB_HOST:$DB_PORT..."
while ! nc -z "$DB_HOST" "$DB_PORT"; do
  sleep 2
done
echo "PostgreSQL is ready."

# Remove Pail service provider from bootstrap/providers.php if it exists
if [ -f bootstrap/providers.php ]; then
    echo "Removing Laravel\Pail\PailServiceProvider from providers list..."
    sed -i '/Laravel\\Pail\\PailServiceProvider/d' bootstrap/providers.php
fi

# Clear any cached service providers
php artisan clear-compiled 2>/dev/null || true
php artisan config:clear 2>/dev/null || true

# Run migrations
php artisan migrate --force || echo "Migration failed, continuing..."

# Run storage link
php artisan storage:link || echo "Storage link failed, continuing..."

# Generate Passport keys
php artisan passport:keys || echo "Passport keys generation failed, continuing..."

# Generate Wayfinder helpers
php artisan wayfinder:generate --with-form || echo "Wayfinder generation failed, continuing..."

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
