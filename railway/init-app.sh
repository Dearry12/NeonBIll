#!/usr/bin/env sh
set -e

php artisan config:clear
php artisan migrate --force
