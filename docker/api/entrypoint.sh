#!/bin/sh
set -e

if [ ! -f vendor/autoload.php ]; then
  composer install --prefer-dist --no-interaction
fi

echo "Waiting for MySQL..."
until mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
  sleep 2
done

php artisan config:clear
php artisan migrate --force
php artisan db:seed --force

exec "$@"
