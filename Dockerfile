# Use PHP 8.2 CLI image
FROM php:8.2-cli

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libonig-dev libxml2-dev libzip-dev \
    sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory inside the container
WORKDIR /var/www/html

# Copy only composer files first (for faster builds)
COPY blog-laravel/composer.json blog-laravel/composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Now copy the full Laravel application from blog-laravel folder
COPY blog-laravel ./

# Create .env from .env.example and generate app key
RUN cp .env.example .env && php artisan key:generate

# Set permissions for storage and cache
RUN chmod -R 777 storage bootstrap/cache

# Expose port for the PHP built-in server
EXPOSE 8000

# Command to run when the container starts
CMD touch /tmp/database.sqlite \
    && php artisan migrate --force \
    && php artisan storage:link || true \
    && php artisan serve --host=0.0.0.0 --port=8000
