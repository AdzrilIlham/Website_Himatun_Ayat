FROM php:8.4-cli-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    unzip \
    zip \
    default-mysql-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mysqli \
        gd \
        zip \
        intl \
        bcmath \
        exif \
        pcntl \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN { \
        echo "upload_max_filesize = 20M"; \
        echo "post_max_size = 25M"; \
        echo "memory_limit = 512M"; \
        echo "realpath_cache_size = 4096K"; \
        echo "realpath_cache_ttl = 600"; \
        echo "opcache.enable = 1"; \
        echo "opcache.enable_cli = 1"; \
        echo "opcache.memory_consumption = 256"; \
        echo "opcache.interned_strings_buffer = 32"; \
        echo "opcache.max_accelerated_files = 30000"; \
        echo "opcache.validate_timestamps = 1"; \
        echo "opcache.revalidate_freq = 1"; \
    } > /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
