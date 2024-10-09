# Тестовое Aйтигро.


## Установка

1. **Клонируйте репозиторий:**
   ```bash
   git clone https://github.com/Kenny201/test_itgro.git
   cd ваш-репозиторий
   ```

2. **Скопируйте файл окружения:**
   ```bash
   cp .env.example .env
   ```

3. **Настройте файл `.env`:**
   Отредактируйте файл `.env` и укажите параметры подключения к вашей базе данных.

4. **Запустите контейнеры:**
   Если вы используете Docker, выполните:
   ```bash
   docker-compose up -d
   ```

## Запуск миграций и заполнение базы данных

После запуска контейнеров выполните следующую команду для применения миграций и заполнения базы данных начальными данными:
```bash
docker exec -it laravel_app  bash
php artisan migrate --seed
```

## Использование

После выполнения миграций и заполнения базы данных вы можете использовать api приложения.
