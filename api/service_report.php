<?php
require_once __DIR__ . "/../includes/config.php";

try {
    $search = $_POST['query']['generalSearch'] ?? '';
    $dateInput = $_POST['query']['date'] ?? '';
    $status = $_POST['query']['status'] ?? '';

    // Konversi format tanggal (dari MM/DD/YYYY → YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('m/d/Y', $dateInput);
    $date    = $dateObj ? $dateObj->format('Y-m-d') : null;

    $sql = "SELECT * FROM service_reports WHERE 1=1";

    $params = [];

    if (!empty($search)) {
        $sql .= " AND (
            srv_id LIKE :search
            OR tanggal LIKE :search
            OR jam LIKE :search
            OR netpay_id LIKE :search
            OR problem LIKE :search
            OR action LIKE :search
            OR part LIKE :search
            OR red_bef LIKE :search
            OR red_aft LIKE :search
            OR pic LIKE :search
            OR keterangan LIKE :search
        )";


        $params[':search'] = "%$search%";
    }

    if (!empty($date)) {
        $sql .= " AND tanggal LIKE :tanggal";
        $params[':tanggal'] = "%$date%";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $service = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "data" => $service
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
