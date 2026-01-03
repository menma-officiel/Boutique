#!/usr/bin/env sh
set -e

# Run migrations and cache config/routes/views
# Intended to be used in deploy/start command on Render or similar platforms
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:clear

echo "Deployment tasks completed"
