<?php
require_once __DIR__ . '/config.php';

function server_pdo(): PDO
{
    $dsn = 'mysql:host=' . DB_HOST . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    create_database();

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function create_database(): void
{
    static $created = false;
    if ($created) {
        return;
    }
    $created = true;

    $pdo = server_pdo();
    $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET ' . DB_CHARSET . ' COLLATE ' . DB_CHARSET . '_unicode_ci');
    $pdo->exec('USE `' . DB_NAME . '`');

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(120) NOT NULL,
        email VARCHAR(160) NOT NULL UNIQUE,
        phone VARCHAR(20),
        password_hash VARCHAR(255) NOT NULL,
        role ENUM('user','admin') NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET);

    $pdo->exec("CREATE TABLE IF NOT EXISTS seats (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        seat_number INT NOT NULL UNIQUE,
        zone VARCHAR(40) NOT NULL DEFAULT 'focus',
        status ENUM('available','reserved','maintenance') NOT NULL DEFAULT 'available'
    ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET);

    $pdo->exec("CREATE TABLE IF NOT EXISTS subscription_plans (
        id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(40) NOT NULL,
        duration_months INT NOT NULL,
        price_inr INT NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET);

    $pdo->exec("CREATE TABLE IF NOT EXISTS reservations (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET);

    $adminHash = password_hash(ADMIN_PASSWORD, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, role)
        VALUES ('Administrator', ?, ?, 'admin')
        ON DUPLICATE KEY UPDATE role = 'admin'");
    $stmt->execute([ADMIN_EMAIL, $adminHash]);

    $plans = [
        [1, '1 Month', 1, 1000],
        [2, '3 Months', 3, 2700],
        [3, '1 Year', 12, 10000],
    ];
    $stmt = $pdo->prepare('INSERT INTO subscription_plans (id, name, duration_months, price_inr) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name), duration_months=VALUES(duration_months), price_inr=VALUES(price_inr)');
    foreach ($plans as $plan) {
        $stmt->execute($plan);
    }

    $seatStmt = $pdo->prepare('INSERT IGNORE INTO seats (seat_number, zone) VALUES (?, ?)');
    for ($i = 1; $i <= 39; $i++) {
        $zone = $i <= 10 ? 'window' : ($i <= 20 ? 'silent' : ($i <= 30 ? 'power' : 'premium'));
        $seatStmt->execute([$i, $zone]);
    }
}
