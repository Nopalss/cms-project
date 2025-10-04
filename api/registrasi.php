<?php
require_once __DIR__ . "/../includes/config.php";

try {
    $search = $_POST['query']['generalSearch'] ?? '';
    $status = $_POST['query']['status'] ?? '';
    $dateInput = $_POST['query']['date'] ?? '';
    // Konversi format tanggal (dari MM/DD/YYYY → YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('m/d/Y', $dateInput);
    $date    = $dateObj ? $dateObj->format('Y-m-d') : null;
    $sql = "SELECT * FROM register WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $sql .= " AND (
                    registrasi_id LIKE :search
                    OR name LIKE :search
                    OR location LIKE :search
                    OR phone LIKE :search
                    OR paket_internet LIKE :search
                    OR is_verified LIKE :search
                )";
        $params[':search'] = "%$search%";
    }

    if (!empty($status)) {
        $sql .= " AND is_verified = :is_verified";
        $params[':is_verified'] = $status;
    }

    if (!empty($date)) {
        $sql .= " AND created_at LIKE :created_at";
        $params[':created_at'] = "%$date%";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $registrasion = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "data" => $registrasion
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
