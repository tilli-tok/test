# Указываем базовый образ
FROM php:8.2-fpm

# Устанавливаем зависимости для Composer и Xdebug
RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    libxml2-dev \
    && docker-php-ext-install xml \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Настройка рабочей директории
WORKDIR /var/www/html

COPY php.ini /usr/local/etc/php/
COPY xdebug.ini /usr/local/etc/php/conf.d/

# Копируем текущий проект в контейнер
COPY . .

# Установка зависимостей проекта через Composer
RUN composer install


# Установка зависимостей Composer
RUN composer install --prefer-dist --no-interaction --optimize-autoloader --no-scripts --no-dev

# Установка PHPUnit (если dev-зависимости нужны в контейнере)
RUN composer install --dev

# Открываем порт
EXPOSE 9000

# Запуск PHP-FPM
CMD ["php-fpm"]