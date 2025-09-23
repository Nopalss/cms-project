<?php
require_once __DIR__ . "/../includes/config.php";

try {
    $search = $_POST['query']['generalSearch'] ?? '';
    $dateInput = $_POST['query']['date'] ?? '';
    $status = $_POST['query']['status'] ?? '';

    // Konversi format tanggal (dari MM/DD/YYYY → YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('m/d/Y', $dateInput);
    $date    = $dateObj ? $dateObj->format('Y-m-d') : null;

    $sql = "SELECT r.*, COALESCE(q.status, 'Not Queued') AS status
        FROM request_dismantle r
        LEFT JOIN queue_scheduling q ON r.rd_id = q.request_id
        WHERE 1=1";

    $params = [];

    if (!empty($search)) {
        $sql .= " AND (
                    r.rm_id LIKE :search
                    OR r.netpay_id LIKE :search
                    OR r.type_issue LIKE :search
                    OR r.deskripsi_issue LIKE :search
                    OR r.request_by LIKE :search
                    OR r.created_at LIKE :search
                    OR q.status LIKE :search
                 )";
        $params[':search'] = "%$search%";
    }

    if (!empty($date)) {
        $sql .= " AND r.created_at LIKE :created_at";
        $params[':created_at'] = "%$date%";
    }
    if (!empty($status)) {
        $sql .= " AND q.status = :status";
        $params[':status'] = $status;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $rd = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "data" => $rd
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
