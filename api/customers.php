<?php
require_once __DIR__ . "/../includes/config.php";

try {
    $search = $_POST['query']['generalSearch'] ?? '';
    $dateInput = $_POST['query']['date'] ?? '';
    $pake_internet = $_POST['query']['paket_internet'] ?? '';
    $status = $_POST['query']['status'] ?? '';

    // Konversi format tanggal (dari MM/DD/YYYY → YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('m/d/Y', $dateInput);
    $date    = $dateObj ? $dateObj->format('Y-m-d') : null;

    $sql = "SELECT * FROM customers WHERE 1=1";

    $params = [];

    if (!empty($search)) {
        $sql .= " AND (
                    netpay_id LIKE :search
                    OR name LIKE :search
                    OR location LIKE :search
                    OR phone LIKE :search
                    OR paket_internet LIKE :search
                )";
        $params[':search'] = "%$search%";
    }

    if (!empty($date)) {
        $sql .= " AND created_at LIKE :created_at";
        $params[':created_at'] = "%$date%";
    }
    if (!empty($status)) {
        $sql .= " AND is_active LIKE :is_active";
        $params[':is_active'] = $status;
    }
    if (!empty($pake_internet)) {
        $sql .= " AND paket_internet LIKE :paket_internet";
        $params[':paket_internet'] = $pake_internet;
    }
    if (!empty($date)) {
        $sql .= " AND created_at LIKE :tanggal";
        $params[':tanggal'] = "%$date%";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $dismantle = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "data" => $dismantle
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
