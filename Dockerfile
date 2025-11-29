# Use official PHP image
FROM php:8.2-cli

# Workdir inside container
WORKDIR /app

# System deps + sqlite extension
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_sqlite

# Copy only the Laravel app folder into container
# Local:  blog-laravel/
# Inside container: /app/
COPY blog-laravel/ .

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Clear caches so latest env load ho
RUN php artisan config:clear && php artisan route:clear && php artisan cache:clear

# Start: migrate, storage link, then serve
CMD php artisan migrate --force && \
    php artisan storage:link || true && \
    php artisan serve --host 0.0.0.0 --port ${PORT:-10000}
