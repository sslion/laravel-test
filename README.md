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

## Использование

1. Консольная команда

```bash
php artisan jokes:fetch
```

Для автоматического запуска каждые 5 минут:

```bash
php artisan schedule:work
```

2. API эндпоинты

Все шутки: http://127.0.0.1:8000/api/jokes
По типу: http://127.0.0.1:8000/api/jokes/{type}
(Необходимо указать свой тестовый домен)

3. Тест фильтрации полей

Откройте: http://127.0.0.1:8000/test-filter.html
(Необходимо указать свой тестовый домен)

4. Счетчик посещений
   Откройте: http://127.0.0.1:8000/test-tracker.html
   (Необходимо указать свой тестовый домен)

5. Статистика
   URL: http://127.0.0.1:8000/stats
   Логин: admin
   Пароль: secret123

🔧 Подключение трекера к любому сайту
Добавьте следующий код на страницу:

```html
<script src="http://127.0.0.1:8000/js/visit-tracker.js"></script>
```

📁 Структура проекта

```text
├── app/
│   ├── Console/
│   │   ├── Commands/
│   │   │   └── FetchJokeCommand.php
│   │   └── Kernel.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── JokeController.php
│   │   │   ├── StatsController.php
│   │   │   └── VisitController.php
│   │   └── Middleware/
│   │       ├── BasicAuth.php
│   │       └── Cors.php
│   └── Models/
│       ├── Joke.php
│       └── Visit.php
├── database/
│   └── migrations/
├── public/
│   └── js/
│       ├── field-filter.js
│       └── visit-tracker.js
├── resources/
│   └── views/
│       └── stats/
│           └── index.blade.php
└── routes/
    ├── api.php
    └── web.php
```
