-- Пользователи 
INSERT INTO users (name, email, role) VALUES
('Анна Петрова', 'anna@example.com', 'manager'),
('Иван Сидоров', 'ivan@example.com', 'user'),
('Мария Волкова', 'maria@example.com', 'user'),
('Олег Кузнецов', 'oleg@example.com', 'user');

-- Профили (1:1)
INSERT INTO user_profiles (user_id, phone, telegram, position) VALUES
(1, '+7-999-111-22-33', '@anna_manager', 'Администратор'),
(2, '+7-999-444-55-66', '@ivan_s', 'Любитель'),
(3, NULL, NULL, NULL), -- пустые поля
(4, '+7-999-777-88-99', '@oleg_k', 'Тренер');

-- Площадки 
INSERT INTO areas (name, description, area_type) VALUES
('Футбольное поле №1', 'Газон, 22 игрока, освещение', 'football'),
('Теннисный корт №2', 'Грунт, 4 игрока', 'tennis'),
('Баскетбольная площадка', 'Асфальт, 10 игроков', 'basketball'),
('Универсальная площадка', 'Для любых игр', 'universal');

-- Инвентарь
INSERT INTO equipment (name, description) VALUES
('Мяч футбольный', 'Размер 5'),
('Мяч баскетбольный', 'Размер 7'),
('Ракетка теннисная', 'Профессиональная');

-- Брони 
INSERT INTO bookings (area_id, user_id, title, status, priority, due_date) VALUES
(1, 2, 'Утренняя игра', 'confirmed', 'high', '2026-10-15'),
(2, 3, 'Тренировка с тренером', 'new', 'medium', '2026-10-16'),
(1, 1, 'Матч выходного дня', 'new', 'low', '2026-10-18'),
(3, 4, 'Игра 3x3', 'confirmed', 'high', '2026-10-14'),
(1, 2, 'Корпоративный турнир', 'new', 'high', '2026-10-20'),
(4, 1, 'Разминка', 'cancelled', 'medium', NULL); -- пустая дата

-- Связь N:M (какой инвентарь выдан на бронь)
INSERT INTO booking_equipment (booking_id, equipment_id, quantity) VALUES
(1, 1, 2), -- На утреннюю игру 2 футбольных мяча
(2, 3, 2), -- На теннис 2 ракетки
(3, 1, 1), -- На матч 1 мяч
(4, 2, 1), -- На баскетбол 1 мяч
(5, 1, 3); -- На турнир 3 мяча