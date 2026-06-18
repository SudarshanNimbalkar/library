<?php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

function json_response(array $payload): never
{
    echo json_encode($payload);
    exit;
}

try {
    $action = $_POST['action'] ?? $_GET['action'] ?? '';
    $pdo = db();

    if ($action === 'register') {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            json_response(['ok' => false, 'message' => 'Please enter a valid name, email and 6+ character password.']);
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (full_name, email, phone, password_hash) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $email, $phone, $hash]);
        json_response(['ok' => true, 'user' => ['id' => (int) $pdo->lastInsertId(), 'name' => $name, 'email' => $email]]);
    }

    if ($action === 'login') {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $stmt = $pdo->prepare('SELECT id, full_name, email, password_hash, role FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['password_hash'])) {
            json_response(['ok' => false, 'message' => 'Invalid email or password.']);
        }
        json_response(['ok' => true, 'user' => ['id' => (int) $user['id'], 'name' => $user['full_name'], 'email' => $user['email'], 'role' => $user['role']]]);
    }

    if ($action === 'tables') {
        $date = $_GET['date'] ?? date('Y-m-d');
        $slot = $_GET['slot'] ?? '';
        $stmt = $pdo->prepare("SELECT s.id, s.seat_number, s.zone, s.status,
            CASE WHEN s.status = 'maintenance' THEN 'maintenance'
                 WHEN r.id IS NOT NULL THEN 'booked'
                 ELSE 'available' END AS live_status
            FROM seats s
            LEFT JOIN reservations r ON r.table_id = s.id
                AND r.booking_date = ?
                AND (? = '' OR r.time_slot = ?)
                AND r.reservation_status = 'reserved'
            ORDER BY s.seat_number");
        $stmt->execute([$date, $slot, $slot]);
        $tables = array_map(static function ($row) {
            return [
                'id' => (int) $row['seat_number'],
                'db_id' => (int) $row['id'],
                'zone' => $row['zone'],
                'seats' => 1,
                'live_status' => $row['live_status'],
            ];
        }, $stmt->fetchAll());
        json_response(['ok' => true, 'tables' => $tables]);
    }

    if ($action === 'book') {
        $userId = (int) ($_POST['user_id'] ?? 0) ?: null;
        $seatNumber = (int) ($_POST['table_id'] ?? 0);
        $date = $_POST['date'] ?? date('Y-m-d');
        $slot = trim($_POST['slot'] ?? 'Full Day');
        $planId = (int) ($_POST['plan_id'] ?? 1);

        $seat = $pdo->prepare('SELECT id FROM seats WHERE seat_number = ? AND status != "maintenance"');
        $seat->execute([$seatNumber]);
        $seatId = $seat->fetchColumn();
        if (!$seatId) {
            json_response(['ok' => false, 'message' => 'Selected table is unavailable.']);
        }
        $plan = $pdo->prepare('SELECT price_inr FROM subscription_plans WHERE id = ?');
        $plan->execute([$planId]);
        $amount = (int) ($plan->fetchColumn() ?: 1000);
        $txn = 'PENDING-' . strtoupper(bin2hex(random_bytes(5)));
        $stmt = $pdo->prepare('INSERT INTO reservations (user_id, table_id, booking_date, time_slot, plan_id, amount_inr, phonepe_transaction_id) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $seatId, $date, $slot, $planId, $amount, $txn]);
        json_response(['ok' => true, 'amount' => $amount, 'transaction_id' => $txn]);
    }

    json_response(['ok' => false, 'message' => 'Unknown action.']);
} catch (Throwable $e) {
    json_response(['ok' => false, 'message' => $e->getMessage()]);
}
