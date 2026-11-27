-- 1. Задача в несуществующей площадке (FOREIGN KEY) - ОШИБКА
INSERT INTO bookings (area_id, user_id, title) VALUES (99, 1, 'Бронь на площадку 99');

-- 2. Неизвестный статус (CHECK) - ОШИБКА
INSERT INTO bookings (area_id, user_id, title, status) VALUES (1, 1, 'Бронь', 'finished');

-- 3. Слишком короткое название (CHECK) - ОШИБКА
INSERT INTO bookings (area_id, user_id, title) VALUES (1, 1, 'Да');

-- 4. Бронь без названия (NOT NULL) - ОШИБКА
INSERT INTO bookings (area_id, user_id, title) VALUES (1, 1, NULL);

-- 5. Повтор почты пользователя (UNIQUE) - ОШИБКА
INSERT INTO users (name, email) VALUES ('Клон', 'anna@example.com');

-- 6. Второй профиль для пользователя (1:1, PRIMARY KEY) - ОШИБКА
INSERT INTO user_profiles (user_id, phone) VALUES (1, '+7-000-000-00-00');

-- 7. Один и тот же инвентарь дважды на одну бронь (N:M, PRIMARY KEY) - ОШИБКА
INSERT INTO booking_equipment (booking_id, equipment_id, quantity) VALUES (1, 1, 5);

-- 8. Удаление площадки, у которой есть брони (ON DELETE RESTRICT) - ОШИБКА
DELETE FROM areas WHERE id = 1;

-- 9. Удаление пользователя, у которого есть брони (ON DELETE RESTRICT) - ОШИБКА
DELETE FROM users WHERE id = 2;

-- 10. Удаление брони удаляет её инвентарь (ON DELETE CASCADE) - УСПЕХ
BEGIN;
DELETE FROM bookings WHERE id = 2;
SELECT COUNT(*) AS equipment_left FROM booking_equipment WHERE booking_id = 2;
ROLLBACK;

-- 11. Удаление пользователя удаляет его профиль (ON DELETE CASCADE) - УСПЕХ
BEGIN;
DELETE FROM users WHERE id = 3;
SELECT COUNT(*) AS profile_left FROM user_profiles WHERE user_id = 3;
ROLLBACK;