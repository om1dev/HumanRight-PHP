FROM php:8.2-apache

# Enable Apache modules
RUN a2enmod rewrite

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    unzip \
    curl \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install MongoDB PHP extension
RUN pecl channel-update pecl.php.net \
    && pecl install mongodb-1.16.2 \
    && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy project files
COPY . .

# Write Apache config directly — no .htaccess dependency
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        Options -Indexes +FollowSymLinks\n\
        AllowOverride None\n\
        Require all granted\n\
        DirectoryIndex index.php\n\
        RewriteEngine On\n\
        RewriteCond %{REQUEST_FILENAME} -f\n\
        RewriteRule ^ - [L]\n\
        RewriteCond %{REQUEST_FILENAME} -d\n\
        RewriteRule ^ - [L]\n\
        RewriteRule ^ /router.php [QSA,L]\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
