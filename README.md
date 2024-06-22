# Ambulance API

## Описание

Ambulance API предоставляет функциональность для управления вызовами скорой помощи, маршрутами, аутентификацией пользователей, регистрацией, верификацией SMS и управлением командами скорой помощи.

## Установка

1. Клонируйте репозиторий:

    ```bash
    git clone https://github.com/ваш-пользователь/ambulance-api.git
    cd ambulance-api
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

5. Настройте базу данных в файле `.env` и выполните миграции:

    ```bash
    php artisan migrate
    ```

6. Запустите сервер разработки:

    ```bash
    php artisan serve
    ```

## API Эндпоинты

### Аутентификация и управление пользователями

#### POST /api/login

Аутентификация пользователей (фельдшеров, водителей, пациентов).

- **Тело запроса:**
    ```json
    {
      "email": "user@example.com",
      "password": "password123"
    }
    ```

- **Ответ:**
    ```json
    {
      "token": "your_token_here"
    }
    ```

#### POST /api/register

Регистрация новых пользователей.

- **Тело запроса:**
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

- **Ответ:**
    ```json
    {
      "id": 1,
      "iin": "123456789012",
      "phone_number": "1234567890",
      "full_name": "John Doe",
      "date_of_birth": "1990-01-01",
      "residence": "123 Main St",
      "created_at": "2024-06-22T10:00:00.000000Z",
      "updated_at": "2024-06-22T10:00:00.000000Z"
    }
    ```

#### POST /api/sms

Получение SMS-кода.

- **Тело запроса:**
    ```json
    {
      "phone_number": "1234567890"
    }
    ```

- **Ответ:**
    ```json
    {
      "message": "SMS sent successfully",
      "code": "1234"
    }
    ```

#### POST /api/verify-sms

Верификация SMS-кода.

- **Тело запроса:**
    ```json
    {
      "sms_code": "1234"
    }
    ```

- **Ответ при успешной верификации:**
    ```json
    {
      "message": "SMS verified successfully"
    }
    ```

- **Ответ при неверном коде:**
    ```json
    {
      "error": "Invalid SMS code"
    }
    ```

#### GET /api/user/profile

Получение информации о профиле пользователя.

- **Ответ:**
    ```json
    {
      "id": 1,
      "iin": "123456789012",
      "phone_number": "1234567890",
      "full_name": "John Doe",
      "date_of_birth": "1990-01-01",
      "residence": "123 Main St",
      "created_at": "2024-06-22T10:00:00.000000Z",
      "updated_at": "2024-06-22T10:00:00.000000Z"
    }
    ```

#### PUT /api/user/profile

Обновление информации о профиле пользователя.

- **Тело запроса:**
    ```json
    {
      "full_name": "John Doe",
      "date_of_birth": "1990-01-01",
      "residence": "123 Main St",
      "phone_number": "1234567890"
    }
    ```

- **Ответ:**
    ```json
    {
      "id": 1,
      "iin": "123456789012",
      "phone_number": "1234567890",
      "full_name": "John Doe",
      "date_of_birth": "1990-01-01",
      "residence": "123 Main St",
      "created_at": "2024-06-22T10:00:00.000000Z",
      "updated_at": "2024-06-22T10:00:00.000000Z"
    }
    ```

### Вызовы скорой помощи

#### POST /api/emergencies

Создание нового вызова скорой помощи.

- **Тело запроса:**
    ```json
    {
      "user_id": 1,
      "address": "34.052235,-118.243683",
      "for_whom": true,
      "status": "pending",
      "driver_name": "John Doe",
      "team_id": 1,
      "call_time": "2024-06-22 10:00:00",
      "review": null,
      "rating": null
    }
    ```

- **Ответ:**
    ```json
    {
      "id": 1,
      "user_id": 1,
      "address": "34.052235,-118.243683",
      "for_whom": true,
      "status": "pending",
      "driver_name": "John Doe",
      "team_id": 1,
      "call_time": "2024-06-22 10:00:00",
      "review": null,
      "rating": null,
      "created_at": "2024-06-22T10:00:00.000000Z",
      "updated_at": "2024-06-22T10:00:00.000000Z"
    }
    ```

#### PUT /api/emergencies/{id}

Обновление информации о вызове скорой помощи.

- **Тело запроса:**
    ```json
    {
      "status": "en_route"
    }
    ```

- **Ответ:**
    ```json
    {
      "id": 1,
      "user_id": 1,
      "address": "34.052235,-118.243683",
      "for_whom": true,
      "status": "en_route",
      "driver_name": "John Doe",
      "team_id": 1,
      "call_time": "2024-06-22 10:00:00",
      "review": null,
      "rating": null,
      "created_at": "2024-06-22T10:00:00.000000Z",
      "updated_at": "2024-06-22T10:00:00.000000Z"
    }
    ```

### Маршруты

#### GET /api/routes/{teamId}

Получить маршрут и координаты команды.

- **Ответ:**
    ```json
    {
      "distance": 4468190,
      "duration": 147058,
      "polyline": "encoded_polyline_here",
      "end_location": {
        "lat": 34.0523511,
        "lng": -118.2435701
      },
      "team_location": "40.712776,-74.005974"
    }
    ```

#### PUT /api/routes/{teamId}

Обновить текущие координаты команды.

- **Тело запроса:**
    ```json
    {
      "current_coordinates": "40.712776,-74.005974"
    }
    ```

- **Ответ:**
    ```json
    {
      "distance": 4468190,
      "duration": 147058,
      "polyline": "encoded_polyline_here",
      "end_location": {
        "lat": 34.0523511,
        "lng": -118.2435701
      },
      "patient_location": "34.052235,-118.243683"
    }
    ```

### Команды скорой помощи

#### POST /api/teams

Создать команду скорой помощи.

- **Тело запроса:**
    ```json
    {
      "car": "Ambulance Car 1",
      "driver": "John Doe",
      "feldsher": "Jane Doe",
      "type": "Type A"
    }
    ```

- **Ответ:**
    ```json
    {
      "id": 1,
      "car": "Ambulance Car 1",
      "driver": "John Doe",
      "feldsher": "Jane Doe",
      "type": "Type A",
      "created_at": "2024-06-22T10:00:00.000000Z",
      "updated_at": "2024-06-22T10:00:00.000000Z"
    }
    ```

#### GET /api/teams

Получить список команд скорой помощи.

- **Ответ:**
    ```json
    [
      {
        "id": 1,
        "car": "Ambulance Car 1",
        "driver": "John Doe",
        "feldsher": "Jane Doe",
        "type": "Type A",
        "created_at": "2024-06-22T10:00:00.000000Z",
        "updated_at": "2024-06-22T10:00:00.000000Z"
      }
    ]
    ```

#### GET /api/teams/{id}

Получить информацию о конкретной команде скорой помощи.

- **Ответ:**
    ```json
    {
      "id": 1,
      "car": "Ambulance Car 1",
      "driver": "John Doe",
      "feldsher": "Jane Doe",
      "type": "Type A",
      "created_at": "2024-06-22T10:00:00.000000Z",
      "updated_at": "2024-06-22T10:00:00.000000Z"
    }
    ```

#### PUT /api/teams/{id}

Обновить информацию о команде скорой помощи.

- **Тело запроса:**
    ```json
    {
      "car": "Ambulance Car 1",
      "driver": "John Doe",
      "feldsher": "Jane Doe",
      "type": "Type A"
    }
    ```

- **Ответ:**
    ```json
    {
      "id": 1,
      "car": "Ambulance Car 1",
      "driver": "John Doe",
      "feldsher": "Jane Doe",
      "type": "Type A",
      "created_at": "2024-06-22T10:00:00.000000Z",
      "updated_at": "2024-06-22T10:00:00.000000Z"
    }
    ```

## Документация Swagger

Документация API доступна по адресу: `/api/documentation`

## Лицензия

Этот проект лицензирован под лицензией MIT. Подробности см. в файле LICENSE.
