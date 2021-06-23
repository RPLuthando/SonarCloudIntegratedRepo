#!/usr/bin/env sh
set -e
php artisan clear-compiled
php artisan cache:clear
php artisan config:cache
php artisan route:clear
php artisan view:clear
composer dump-autoload -o
exec "$@"