# FROM php:7.2-fpm
FROM public.ecr.aws/e6w7z7d4/php-baseimage:latest

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    nginx \
    supervisor \
    && mkdir -p /run/nginx

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# stdout configuration for nginx logs
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

# Add user for laravel application
# RUN groupadd -g 1000 www
# RUN useradd -u 1000 -ms /bin/bash -g www www

# Configure PHP-FPM
COPY ./docker-config/php/php.ini "$PHP_INI_DIR/php.ini"
COPY ./docker-config/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Configure nginx
COPY ./docker-config/nginx/app.conf /etc/nginx/sites-enabled/default

# Configure supervisord
COPY ./docker-config/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy existing application directory contents
COPY . /var/www/html

# composer update & installation
RUN composer require tymon/jwt-auth:^1.0
RUN composer install --ignore-platform-reqs



RUN php artisan cache:clear
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan view:clear




# Copy existing application directory permissions
# COPY --chown=www:www . /var/www/html
RUN chmod -R 0777 /var/www/html


# RUN chown -R www:www /var/lib/nginx /run 

# Change current user to www
# USER www

# Expose port 9000 and start php-fpm and Nginx server
EXPOSE 9000 80

#CMD ["php-fpm"]
# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
