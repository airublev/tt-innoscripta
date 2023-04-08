# Use the official PHP image with Apache as the base image
FROM php:8.1-apache

# Install required dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    default-mysql-client \
    && docker-php-ext-configure gd \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) gd mbstring dom zip pdo_mysql pdo_pgsql

# Enable Apache mod_rewrite for URL rewriting
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html/server

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy other application files
COPY . /var/www/html/server

# Set file permissions
RUN chown -R www-data:www-data /var/www/html/server \
    && chmod -R 755 /var/www/html/server/storage \
    && chmod -R 755 /var/www/html/server/bootstrap/cache


# Expose port 80
EXPOSE 80
