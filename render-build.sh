#!/usr/bin/env bash
set -e

echo "=== Running Render Build Script for Nishan Portfolio ==="

# 1. Install PHP dependencies
echo "--> Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# 2. Setup .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "--> Creating .env from .env.example..."
    cp .env.example .env
fi

# 3. Install Node dependencies and compile assets
echo "--> Building frontend assets with Vite..."
npm ci || npm install
npm run build

# 4. Generate application key if missing
if ! grep -q "APP_KEY=base64:" .env && [ -z "$APP_KEY" ]; then
    echo "--> Generating application key..."
    php artisan key:generate --force
fi

# 5. Clear and cache configuration, routes, and views
echo "--> Optimizing Laravel caches..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Build Complete! Ready for startup ==="
