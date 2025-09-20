<?php
require_once __DIR__ . "/../includes/config.php";

try {
    $search = $_POST['query']['generalSearch'] ?? '';
    $status = $_POST['query']['status'] ?? '';
    $tech = $_POST['query']['tech_id'] ?? '';
    $dateInput = $_POST['query']['date'] ?? '';
    // Konversi format tanggal (dari MM/DD/YYYY → YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('m/d/Y', $dateInput);
    $date    = $dateObj ? $dateObj->format('Y-m-d') : null;
    $sql = "SELECT s.schedule_id, s.netpay_id ,s.tech_id, s.date, s.time, c.location, s.job_type, s.status, t.name AS technician_name FROM schedules s LEFT JOIN technician t ON s.tech_id = t.tech_id JOIN customers c ON s.netpay_id = c.netpay_id WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $sql .= " AND (s.schedule_id LIKE :search
               OR s.tech_id LIKE :search
               OR s.netpay_id LIKE :search
               OR s.date LIKE :search
               OR s.time LIKE :search
               OR s.location LIKE :search
               OR s.job_type LIKE :search
               OR s.status LIKE :search
               OR t.name LIKE :search)";
        $params[':search'] = "%$search%";
    }


    if (!empty($status)) {
        $sql .= " AND s.status = :status";
        $params[':status'] = $status;
    }

    if (!empty($tech)) {
        $sql .= " AND s.tech_id = :tech_id";
        $params[':tech_id'] = $tech;
    }
    if (!empty($date)) {
        $sql .= " AND s.date = :date";
        $params[':date'] = $date;
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "data" => $schedules
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
