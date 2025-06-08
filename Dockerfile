# core-service/Dockerfile
FROM php:8.3-fpm

# Cài thêm các extension cần thiết cho Laravel
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    && docker-php-ext-install pdo_mysql sockets zip mbstring exif pcntl bcmath gd curl

WORKDIR /var/www/core

COPY composer.json composer.lock ./
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies without scripts first
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy the rest of the application files
COPY . .

# Copy environment file
# COPY .env /var/www/html/.env
RUN chown -R www-data:www-data /var/www/core/storage /var/www/core/bootstrap/cache
RUN chmod -R 775 /var/www/core/storage /var/www/core/bootstrap/cache

# Run the post-install scripts now that all files are available
# RUN composer run-script post-autoload-dump

EXPOSE 8081

CMD ["php-fpm"]