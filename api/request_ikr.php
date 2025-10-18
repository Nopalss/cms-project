<?php
require_once __DIR__ . "/../includes/config.php";

try {
    $search = $_POST['query']['generalSearch'] ?? '';
    $dateInput = $_POST['query']['date'] ?? '';
    $status = $_POST['query']['status'] ?? '';

    // Konversi format tanggal (dari MM/DD/YYYY → YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('m/d/Y', $dateInput);
    $date    = $dateObj ? $dateObj->format('Y-m-d') : null;

    $sql = "SELECT r.*, COALESCE(q.status, 'Not Queued') AS status, rg.registrasi_id, c.netpay_id
        FROM request_ikr r
        LEFT JOIN queue_scheduling q ON r.rikr_id = q.request_id
        LEFT JOIN register rg ON r.registrasi_key = rg.registrasi_key
        LEFT JOIN customers c ON r.netpay_key = c.netpay_key
        WHERE 1=1";

    $params = [];

    if (!empty($search)) {
        $sql .= " AND (
                    r.rikr_id LIKE :search
                    OR c.netpay_id LIKE :search
                    OR rg.registrasi_id LIKE :search
                    OR r.jadwal_pemasangan LIKE :search
                    OR r.catatan LIKE :search
                    OR r.request_by LIKE :search
                    OR q.status LIKE :search
                 )";
        $params[':search'] = "%$search%";
    }

    if (!empty($date)) {
        $sql .= " AND r.jadwal_pemasangan LIKE :jadwal_pemasangan";
        $params[':jadwal_pemasangan'] = "%$date%";
    }
    if (!empty($status)) {
        $sql .= " AND q.status = :status";
        $params[':status'] = $status;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $rikr = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "data" => $rikr
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
