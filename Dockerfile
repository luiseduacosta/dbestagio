FROM php:8.2-apache

# Install system dependencies and required PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        mysqli \
        pdo \
        pdo_mysql \
        gd \
        zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Install Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Apache configuration for dbestagio
COPY docker/apache.conf /etc/apache2/conf-available/estagio.conf
RUN a2enconf estagio

# Set working directory
WORKDIR /var/www/html

# Copy application source code
COPY . /var/www/html/

# Create and grant permissions for Smarty cache and temp directories
RUN mkdir -p /var/www/html/smarty/cache \
             /var/www/html/smarty/templates_c \
             /var/www/html/tmp \
    && chown -R www-data:www-data /var/www/html/smarty/cache \
                                 /var/www/html/smarty/templates_c \
                                 /var/www/html/tmp \
    && chmod -R 775 /var/www/html/smarty/cache \
                    /var/www/html/smarty/templates_c \
                    /var/www/html/tmp

EXPOSE 80

CMD ["apache2-foreground"]
