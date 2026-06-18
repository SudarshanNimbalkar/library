CREATE DATABASE IF NOT EXISTS saraswati_library CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE saraswati_library;

CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS seats (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    seat_number INT NOT NULL UNIQUE,
    zone VARCHAR(40) NOT NULL DEFAULT 'focus',
    status ENUM('available','reserved','maintenance') NOT NULL DEFAULT 'available'
);

CREATE TABLE IF NOT EXISTS subscription_plans (
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(40) NOT NULL,
    duration_months INT NOT NULL,
    price_inr INT NOT NULL
);

CREATE TABLE IF NOT EXISTS reservations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    table_id INT UNSIGNED NOT NULL,
    booking_date DATE NOT NULL,
    time_slot VARCHAR(60) NOT NULL,
    plan_id TINYINT UNSIGNED NULL,
    amount_inr INT NOT NULL DEFAULT 0,
    payment_status ENUM('pending','paid','failed','refunded') DEFAULT 'pending',
    reservation_status ENUM('reserved','cancelled','completed') DEFAULT 'reserved',
    phonepe_transaction_id VARCHAR(120),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_table_slot (table_id, booking_date, time_slot),
    CONSTRAINT fk_res_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_res_seat FOREIGN KEY (table_id) REFERENCES seats(id) ON DELETE CASCADE,
    CONSTRAINT fk_res_plan FOREIGN KEY (plan_id) REFERENCES subscription_plans(id) ON DELETE SET NULL
);
