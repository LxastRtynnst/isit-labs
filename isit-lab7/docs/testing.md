# Результаты тестирования API

## Брони
| № | Запрос | Ожидаемый код | Получен код | Комментарий |
|---|---|---|---|---|
| 1 | GET /api/bookings | 200 | 200 | массив из 6 броней |
| 2 | POST /api/bookings | 201 | 201 | Location: /api/bookings/6 |
| 3 | PATCH /api/bookings/6 | 200 | 200 | статус изменён |
| 4 | DELETE /api/bookings/6 | 204 | 204 | тела нет |
| 5 | GET /api/bookings/999 | 404 | 404 | {"error": "Бронь не найдена"} |
| 6 | POST /api/bookings с коротким title | 422 | 422 | errors.title, errors.area_id |

## Площадки и пользователи
| № | Запрос | Ожидаемый код | Получен код | Комментарий |
|---|---|---|---|---|
| 7 | GET /api/areas | 200 | 200 | список площадок |
| 8 | GET /api/areas/1/bookings | 200 | 200 | брони площадки |
| 9 | DELETE /api/areas/1 | 409 | 409 | есть брони |
| 10 | GET /api/users | 200 | 200 | список пользователей |
| 11 | DELETE /api/users | 405 | 405 | Allow: GET, POST |