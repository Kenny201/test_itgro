# Используем базовый PHP образ
FROM php:8.3-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Устанавливаем рабочую директорию
WORKDIR /var/www

# Устанавливаем зависимости через composer
COPY ../../ /var/www
RUN composer install

# Убедитесь, что права доступа к директориям корректны
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

# Открываем порт для artisan serve
EXPOSE 8000
