<?php
require_once __DIR__ . "/../includes/config.php";

try {
    $search = $_POST['query']['generalSearch'] ?? '';
    $dateInput = $_POST['query']['date'] ?? '';
    $status = $_POST['query']['status'] ?? '';
    $type = $_POST['query']['type'] ?? '';

    // Konversi format tanggal (dari MM/DD/YYYY → YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('m/d/Y', $dateInput);
    $date    = $dateObj ? $dateObj->format('Y-m-d') : null;

    $sql = "SELECT * FROM queue_scheduling WHERE 1=1";

    $params = [];

    if (!empty($search)) {
        $sql .= " AND (
                    queue_id LIKE :search
                    OR type_queue LIKE :search
                    OR request_id LIKE :search
                    OR status LIKE :search
                    OR created_at LIKE :search
                 )";
        $params[':search'] = "%$search%";
    }


    if (!empty($status)) {
        $sql .= " AND status = :status";
        $params[':status'] = $status;
    }
    if (!empty($date)) {
        $sql .= " AND DATE(created_at) = :created_at";
        $params[':created_at'] = $date; // tanpa %
    }
    if (!empty($type)) {
        $sql .= " AND type_queue = :type";
        $params[':type'] = $type;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $queue = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "data" => $queue
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
