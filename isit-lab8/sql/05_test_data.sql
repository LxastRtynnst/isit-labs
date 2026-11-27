-- Добавляем 1000 площадок
INSERT INTO areas (name, area_type)
SELECT 'Площадка ' || i, 'universal'
FROM generate_series(1, 1000) AS i;

-- Добавляем 1000 пользователей
INSERT INTO users (name, email)
SELECT 'Юзер ' || i, 'user' || i || '@example.com'
FROM generate_series(1, 1000) AS i;

-- Добавляем 100 000 броней (распределяем по случайным площадкам и юзерам)
INSERT INTO bookings (area_id, user_id, title, status, priority, due_date)
SELECT
    (random() * 999 + 1)::INTEGER,
    (random() * 999 + 1)::INTEGER,
    'Тестовая бронь ' || i,
    (ARRAY['new', 'confirmed', 'cancelled'])[floor(random() * 3 + 1)],
    (ARRAY['low', 'medium', 'high'])[floor(random() * 3 + 1)],
    '2026-01-01'::DATE + (random() * 300)::INTEGER
FROM generate_series(1, 100000) AS i;