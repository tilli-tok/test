Проект на PHP с использованием Docker и Xdebug
PHP-приложение, развернутое с использованием Docker и Docker Compose. Включает в себя настройку веб-сервера Nginx, PHP 8.2, Xdebug для отладки, и Composer для управления зависимостями.

Требования
Docker
Docker Compose

Установка и запуск проекта
1. Клонируйте репозиторий
  bash
  git clone https://github.com/tilli-tok/test.git


2. Настройка окружения
Установка зависимостей
  bash
  docker-compose run --rm php composer install
3. Запуск контейнеров
Для запуска проекта выполните следующую команду:
  bash
  docker-compose up -d
  Проект будет доступен по адресу http://localhost:8080.

4. Отладка с использованием Xdebug
Для использования Xdebug настройте ваш IDE (PHPStorm) для работы с Xdebug. Убедитесь, что IDE слушает порт 9003, и отладчик Xdebug подключится автоматически.

Структура проекта

test/
├── Dockerfile
├── docker-compose.yml
├── php.ini
├── xdebug.ini
├── nginx.conf
├── composer.json
└── public/
    └── index.php
    
Dockerfile: Описание образа Docker для PHP.
docker-compose.yml: Конфигурация для Docker Compose.
php.ini: Настройки PHP.
xdebug.ini: Настройки Xdebug для отладки.
nginx.conf: Конфигурация веб-сервера Nginx.
composer.json: Зависимости проекта.
public/index.php: Главный входной файл для PHP-приложения.

Отладка и Логи
Логи PHP и Xdebug можно найти в контейнере в /var/log/xdebug.log.
Для просмотра логов сервера и других компонентов используйте команду docker-compose logs.

Дополнительные команды
Перестроить контейнеры после изменения Dockerfile:
  bash
  docker-compose build
Запуск контейнеров в режиме отладки:
  bash
  docker-compose up --build
