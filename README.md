# Тестовое задание PHP Developer

## 📋 Описание

Проект состоит из трех частей:

1. Laravel приложение с API шуток и планировщиком
2. JS скрипт для фильтрации полей
3. Счетчик посещений с статистикой

## 🚀 Установка

### Требования

- PHP 8.0+
- Composer
- SQLite

### Шаги установки

1. Клонируйте репозиторий:

```bash
git clone [https://github.com/sslion/laravel-test.git]
cd laravel-test
```

2. Установите зависимости:

```bash
composer install
```

3. Создайте файл окружения:

```bash
cp .env.example .env
```

4. Настройте базу данных в .env:

```bash
DB_CONNECTION=sqlite
```

5. Создайте файл БД:

```bash
touch database/database.sqlite
```

6. Сгенерируйте ключ:

```bash
php artisan key:generate
```

7. Запустите миграции:

```bash
php artisan migrate
```

8. Запустите сервер:

```bash
php artisan serve
```
