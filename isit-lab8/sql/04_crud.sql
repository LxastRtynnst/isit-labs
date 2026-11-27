-- === CREATE ===
INSERT INTO bookings (area_id, user_id, title, priority, due_date)
VALUES (1, 2, 'Новая бронь из SQL', 'high', '2026-11-20')
RETURNING id, title, status;

-- === READ (список с фильтром) ===
SELECT id, title, status, due_date
FROM bookings
WHERE status = 'confirmed'
ORDER BY due_date;

-- === READ (одна запись) ===
SELECT * FROM bookings WHERE id = 1;

-- === READ (JOIN) ===
SELECT b.id, b.title, a.name AS area_name, u.name AS user_name, b.status
FROM bookings b
JOIN areas a ON a.id = b.area_id
JOIN users u ON u.id = b.user_id
ORDER BY b.id;

-- === READ (LEFT JOIN: брони без указания даты) ===
SELECT b.id, b.title, a.name AS area_name
FROM bookings b
LEFT JOIN areas a ON a.id = b.area_id
WHERE b.due_date IS NULL;

-- === READ (N:M: какой инвентарь на бронях) ===
SELECT b.id AS booking_id, b.title,
       string_agg(e.name || ' (' || be.quantity || ' шт.)', ', ') AS equipment_list
FROM bookings b
JOIN booking_equipment be ON be.booking_id = b.id
JOIN equipment e ON e.id = be.equipment_id
GROUP BY b.id, b.title
ORDER BY b.id;

-- === READ (GROUP BY: отчет по площадкам) ===
SELECT a.name AS area_name, COUNT(b.id) AS total_bookings,
       COUNT(b.id) FILTER (WHERE b.status = 'confirmed') AS confirmed_bookings
FROM areas a
LEFT JOIN bookings b ON b.area_id = a.id
GROUP BY a.id, a.name
ORDER BY total_bookings DESC;

-- === UPDATE ===
UPDATE bookings SET status = 'confirmed' WHERE id = 1 RETURNING id, title, status;

-- === DELETE ===
DELETE FROM bookings WHERE id = 4 RETURNING id, title;