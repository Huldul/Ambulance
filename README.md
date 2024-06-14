
# Ambulance API

Это проект для управления системой скорой помощи. Проект предоставляет API для аутентификации пользователей, управления их профилями и других функций.

## Технологии

- PHP 8.2
- Laravel 10.10
- MySQL
- Redis
- Laravel Passport для аутентификации
- Swagger для документации API

## Установка

1. Клонируйте репозиторий:

   ```bash
   git clone https://github.com/yourusername/ambulance.git
   cd ambulance
   ```

2. Установите зависимости:

   ```bash
   composer install
   ```

3. Скопируйте файл `.env.example` в `.env` и настройте параметры окружения:

   ```bash
   cp .env.example .env
   ```

4. Сгенерируйте ключ приложения:

   ```bash
   php artisan key:generate
   ```

5. Запустите миграции и сиды для базы данных:

   ```bash
   php artisan migrate
   ```

6. Установите Laravel Passport и создайте ключи:

   ```bash
   php artisan passport:install
   ```

## Запуск

1. Запустите локальный сервер разработки:

   ```bash
   php artisan serve
   ```

2. Откройте браузер и перейдите по адресу `http://127.0.0.1:8000`.

## Документация API

Документация API создана с использованием Swagger. Чтобы просмотреть документацию, выполните следующие шаги:

1. Установите пакет L5-Swagger:

   ```bash
   composer require darkaonline/l5-swagger
   ```

2. Опубликуйте конфигурацию L5-Swagger:

   ```bash
   php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
   ```

3. Перейдите по адресу `http://127.0.0.1:8000/api/documentation` для просмотра документации.

## Примеры запросов API

### Регистрация пользователя

**Метод**: `POST`
**URL**: `/api/register`

**Параметры запроса**:

```json
{
    "iin": "123456789012",
    "phone_number": "1234567890",
    "full_name": "John Doe",
    "date_of_birth": "1990-01-01",
    "residence": "123 Main St",
    "password": "password123"
}
```

### Аутентификация пользователя

**Метод**: `POST`
**URL**: `/api/login`

**Параметры запроса**:

```json
{
    "iin": "123456789012",
    "password": "password123"
}
```

### Отправка SMS

**Метод**: `POST`
**URL**: `/api/sms`

### Подтверждение SMS

**Метод**: `POST`
**URL**: `/api/verify-sms`

**Параметры запроса**:

```json
{
    "sms_code": "1234"
}
```

### Получение профиля пользователя

**Метод**: `GET`
**URL**: `/api/user/profile`

**Заголовок**:

```
Authorization: Bearer <your-access-token>
```

### Обновление профиля пользователя

**Метод**: `PUT`
**URL**: `/api/user/profile`

**Заголовок**:

```
Authorization: Bearer <your-access-token>
```

**Параметры запроса**:

```json
{
    "phone_number": "0987654321",
    "full_name": "John Smith",
    "date_of_birth": "1991-02-02",
    "residence": "456 Elm St"
}
```

## Лицензия

Этот проект лицензируется на условиях MIT License.
