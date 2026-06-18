<?php
require_once __DIR__ . '/../config/config.php';

create_database();

function create_database()
{
    $conn = db();
    $stmt = $conn->query("
    SELECT SCHEMA_NAME
    FROM INFORMATION_SCHEMA.SCHEMATA
    WHERE SCHEMA_NAME = '" . DB_NAME . "'
");
    if ($stmt->fetch()) {
        return; // Database already exists, stop here
    }
    $statements = [
        "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET " . DB_CHARSET . ";",
        "USE " . DB_NAME . ";",
        "CREATE TABLE IF NOT EXISTS users (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(120) NOT NULL,
 email VARCHAR(160) NOT NULL UNIQUE,
 phone VARCHAR(20),
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('user','admin') NOT NULL DEFAULT 'user',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);",
        "INSERT INTO users (
    full_name,
    email,
    password_hash,
    role
) VALUES (
    'Administrator',
    'admin@gmail.com',
    'admin123',
    'admin'
);",

        "CREATE TABLE
        seats (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            seat_number INT NOT NULL UNIQUE,
            status ENUM ('available', 'reserved', 'maintenance') DEFAULT 'available'
        );",
        "INSERT INTO seats(seat_number) SELECT n FROM (SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19 UNION SELECT 20 UNION SELECT 21 UNION SELECT 22 UNION SELECT 23 UNION SELECT 24 UNION SELECT 25 UNION SELECT 26 UNION SELECT 27 UNION SELECT 28 UNION SELECT 29 UNION SELECT 30 UNION SELECT 31 UNION SELECT 32 UNION SELECT 33 UNION SELECT 34 UNION SELECT 35 UNION SELECT 36 UNION SELECT 37 UNION SELECT 38 UNION SELECT 39) seats;",

        "CREATE TABLE IF NOT EXISTS subscription_plans (
 id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(40) NOT NULL,
 duration_months INT NOT NULL,
 price_inr INT NOT NULL
);",
        "CREATE TABLE IF NOT EXISTS reservations (
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
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (table_id) REFERENCES seats(id) ON DELETE CASCADE
);"
    ];
    foreach ($statements as $sql) {
        $conn->exec($sql);
    }
}

// DSN (Data Source Name).
function db(): PDO
{
    $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        // Set error mode to exceptions
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
