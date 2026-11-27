
DROP TABLE IF EXISTS booking_equipment CASCADE;
DROP TABLE IF EXISTS equipment CASCADE;
DROP TABLE IF EXISTS bookings CASCADE;
DROP TABLE IF EXISTS areas CASCADE;
DROP TABLE IF EXISTS user_profiles CASCADE;
DROP TABLE IF EXISTS users CASCADE;


CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    role VARCHAR(20) NOT NULL DEFAULT 'user' CHECK (role IN ('manager', 'user')),
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);


CREATE TABLE user_profiles (
    user_id INTEGER PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
    phone VARCHAR(20),
    telegram VARCHAR(50) UNIQUE,
    position VARCHAR(100)
);


CREATE TABLE areas (
    id SERIAL PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    description TEXT,
    area_type VARCHAR(50) NOT NULL DEFAULT 'universal' CHECK (area_type IN ('football', 'basketball', 'tennis', 'universal')),
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);


CREATE TABLE bookings (
    id SERIAL PRIMARY KEY,
    area_id INTEGER NOT NULL REFERENCES areas(id) ON DELETE RESTRICT,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    title VARCHAR(200) NOT NULL CHECK (char_length(title) >= 3),
    status VARCHAR(20) NOT NULL DEFAULT 'new' CHECK (status IN ('new', 'confirmed', 'cancelled')),
    priority VARCHAR(10) NOT NULL DEFAULT 'medium' CHECK (priority IN ('low', 'medium', 'high')),
    due_date DATE,
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);


CREATE TABLE equipment (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);


CREATE TABLE booking_equipment (
    booking_id INTEGER NOT NULL REFERENCES bookings(id) ON DELETE CASCADE,
    equipment_id INTEGER NOT NULL REFERENCES equipment(id) ON DELETE CASCADE,
    quantity INTEGER NOT NULL DEFAULT 1 CHECK (quantity > 0),
    PRIMARY KEY (booking_id, equipment_id)
);