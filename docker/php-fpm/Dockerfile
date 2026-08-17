# Stage 1: Builder - install dependencies
FROM php:8.5-fpm AS builder

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    unzip \
    libpq-dev \
    libonig-dev \
    libssl-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    intl \
    zip \
    bcmath \
    soap \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www

# Copy application code
COPY . /var/www

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --prefer-dist

# Stage 2: Production image
FROM php:8.5-fpm AS production

# Install runtime libraries only
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    libfcgi-bin \
    procps \
    && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    intl \
    zip \
    bcmath \
    #soap \
    && pecl install redis \
    && docker-php-ext-enable redis

WORKDIR /var/www

# Copy application code and dependencies from builder
COPY --from=builder /var/www /var/www
COPY --from=builder /usr/local/bin/composer /usr/local/bin/composer

# Set permissions
RUN chown -R www-data:www-data /var/www

# Use production PHP configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
