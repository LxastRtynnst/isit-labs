# Документация REST API «Бронирование спортивных площадок»

Базовый адрес: `http://localhost:8000`. Формат данных: JSON (UTF-8). Все адреса начинаются с `/api`.
Запуск: `cd lab7/src && php -S localhost:8000 -t public`.

## Сводная таблица

| Метод | URL | Назначение | Успешный код |
|---|---|---|---|
| GET | `/api/bookings` | список броней (фильтры `status`, `area_id`) | 200 |
| GET | `/api/bookings/{id}` | бронь по номеру | 200 |
| POST | `/api/bookings` | создать бронь | 201 |
| PUT, PATCH | `/api/bookings/{id}` | изменить бронь | 200 |
| DELETE | `/api/bookings/{id}` | удалить бронь | 204 |
| GET | `/api/areas` | список площадок | 200 |
| GET | `/api/areas/{id}` | площадка по номеру | 200 |
| GET | `/api/areas/{id}/bookings` | брони площадки | 200 |
| POST | `/api/areas` | создать площадку | 201 |
| PUT, PATCH | `/api/areas/{id}` | изменить площадку | 200 |
| DELETE | `/api/areas/{id}` | удалить площадку (только без броней) | 204 |
| GET | `/api/users` | список пользователей | 200 |
| GET | `/api/users/{id}` | пользователь по номеру | 200 |
| POST | `/api/users` | добавить пользователя | 201 |
| PUT, PATCH | `/api/users/{id}` | изменить пользователя | 200 |
| DELETE | `/api/users/{id}` | удалить пользователя | 204 |

## Общие правила

- Тело запросов `POST`, `PUT`, `PATCH` — JSON-объект.
- Ошибки возвращаются в едином формате: `{"error": "текст"}`, а для ошибок проверки ещё и `"errors": {"поле": "текст"}`.
- `PUT` и `PATCH` обрабатываются одинаково: присланные поля заменяют старые, остальные остаются. Поле `id` изменить нельзя.

| Код | Когда |
|---|---|
| 400 | тело запроса — не корректный JSON-объект |
| 404 | нет записи или адреса |
| 405 | адрес есть, но не для этого метода (в заголовке `Allow` — разрешённые) |
| 409 | удаление площадки, у которой есть брони |
| 422 | данные не прошли проверку |

---

## Ресурс «Брони»

Поля: `id` (число), `area_id` (число, существующая площадка), `title` (строка, от 3 символов), `status` (new, confirmed, cancelled), `priority` (low, medium, high), `due_date` (строка ГГГГ-ММ-ДД или null).

### GET /api/bookings — список броней

Параметры строки запроса (необязательные): `status` — фильтр по статусу; `area_id` — фильтр по площадке.

**Запрос:**
```http
GET /api/bookings?status=confirmed
```

**Ответ 200 OK:**
```json
[
    {
        "id": 1,
        "area_id": 1,
        "title": "Утренняя игра",
        "status": "confirmed",
        "priority": "high",
        "due_date": "2026-10-15"
    }
]
```

### GET /api/bookings/{id} — бронь по номеру

**Запрос:**
```http
GET /api/bookings/3
```

**Ответ 200 OK:**
```json
{
    "id": 3,
    "area_id": 1,
    "title": "Матч выходного дня",
    "status": "new",
    "priority": "low",
    "due_date": "2026-10-18"
}
```

**Ошибка 404:** `{"error": "Бронь не найдена"}`

### POST /api/bookings — создать бронь

Тело (JSON): обязательные `title`, `area_id`; необязательные `status` (по умолчанию `new`), `priority` (по умолчанию `medium`), `due_date` (по умолчанию `null`).

**Запрос:**
```http
POST /api/bookings
Content-Type: application/json

{ "title": "Тестовая игра", "area_id": 1 }
```

**Ответ 201 Created, заголовок `Location: /api/bookings/6`:**
```json
{
    "id": 6,
    "area_id": 1,
    "title": "Тестовая игра",
    "status": "new",
    "priority": "medium",
    "due_date": null
}
```

**Ошибка 400:** не JSON.
**Ошибка 422:**
```json
{
    "error": "Ошибка проверки данных",
    "errors": {
        "title": "Название — не короче 3 символов",
        "area_id": "Площадки с таким номером нет"
    }
}
```

### PUT, PATCH /api/bookings/{id} — изменить бронь

Тело (JSON): любые из полей брони.

**Запрос:**
```http
PATCH /api/bookings/6
Content-Type: application/json

{ "status": "confirmed" }
```

**Ответ 200 OK:** обновлённый объект брони.

**Ошибки:** 404, 400, 422.

### DELETE /api/bookings/{id} — удалить бронь

**Запрос:**
```http
DELETE /api/bookings/6
```

**Ответ 204 No Content** (тела нет).

**Повторный запрос:** 404, `{"error": "Бронь не найдена"}`.

---

## Ресурс «Площадки»

Поля: `id` (число), `name` (строка, от 3 символов), `description` (строка, необязательное).

### GET /api/areas — список площадок

**Ответ 200 OK:**
```json
[
    { "id": 1, "name": "Футбольное поле №1", "description": "Газон, 22 игрока, освещение" },
    { "id": 2, "name": "Теннисный корт №2", "description": "Грунт, 4 игрока" },
    { "id": 3, "name": "Баскетбольная площадка", "description": "Асфальт, 10 игроков" }
]
```

### GET /api/areas/{id} — площадка по номеру

**Запрос:**
```http
GET /api/areas/2
```

**Ответ 200 OK:** объект площадки. **Ошибка 404:** `{"error": "Площадка не найдена"}`

### GET /api/areas/{id}/bookings — брони площадки

**Запрос:**
```http
GET /api/areas/1/bookings
```

**Ответ 200 OK:** массив броней этой площадки. **Ошибка 404:** если площадки нет.

### POST /api/areas — создать площадку

**Запрос:**
```http
POST /api/areas
Content-Type: application/json

{ "name": "Новая площадка", "description": "Описание" }
```

**Ответ 201 Created, `Location: /api/areas/4`:** созданный объект.

**Ошибки:** 400, 422.

### PUT, PATCH /api/areas/{id} — изменить площадку

**Запрос:**
```http
PATCH /api/areas/4
Content-Type: application/json

{ "description": "Новое описание" }
```

**Ответ 200 OK:** обновлённый объект.

**Ошибки:** 404, 400, 422.

### DELETE /api/areas/{id} — удалить площадку

**Запрос:**
```http
DELETE /api/areas/4
```

**Ответ 204 No Content.**

**Ошибки:** 404; **409** если у площадки есть брони: `{"error": "Нельзя удалить площадку: у неё есть брони"}`.

---

## Ресурс «Пользователи»

Поля: `id` (число), `name` (строка, от 2 символов), `email` (корректный адрес), `role` (manager, user).

### GET /api/users — список пользователей

**Ответ 200 OK:**
```json
[
    { "id": 1, "name": "Анна Петрова", "email": "anna@example.com", "role": "manager" },
    { "id": 2, "name": "Иван Сидоров", "email": "ivan@example.com", "role": "user" }
]
```

### GET /api/users/{id} — пользователь по номеру

**Запрос:**
```http
GET /api/users/2
```

**Ответ 200 OK:** объект пользователя. **Ошибка 404:** `{"error": "Пользователь не найден"}`

### POST /api/users — добавить пользователя

Тело: `name`, `email`, `role` (все обязательные).

**Запрос:**
```http
POST /api/users
Content-Type: application/json

{ "name": "Олег Орлов", "email": "oleg@example.com", "role": "user" }
```

**Ответ 201 Created, `Location: /api/users/3`:** созданный объект.

**Ошибки:** 400, 422 (например, невалидный email).

### PUT, PATCH /api/users/{id} — изменить пользователя

**Запрос:**
```http
PATCH /api/users/3
Content-Type: application/json

{ "role": "manager" }
```

**Ответ 200 OK:** обновлённый объект.

**Ошибки:** 404, 400, 422.

### DELETE /api/users/{id} — удалить пользователя

**Запрос:**
```http
DELETE /api/users/3
```

**Ответ 204 No Content. Ошибка 404.**

**Запрос `DELETE /api/users` (без номера)** → **405 Method Not Allowed**, заголовок `Allow: GET, POST`.