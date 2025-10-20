<?php
require_once __DIR__ . "/../includes/config.php";

try {
    $search = $_POST['query']['generalSearch'] ?? '';
    $dateInput = $_POST['query']['date'] ?? '';
    $status = $_POST['query']['status'] ?? '';

    // Konversi format tanggal (dari MM/DD/YYYY → YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('m/d/Y', $dateInput);
    $date    = $dateObj ? $dateObj->format('Y-m-d') : null;

    $sql = "SELECT s.*, c.netpay_id FROM service_reports s LEFT JOIN customers c ON s.netpay_key = c.netpay_key WHERE 1=1";


    $params = [];

    $params = [];
    if ($_SESSION['role'] == 'teknisi') {
        $sql .= ' AND s.pic = :pic';
        $params[":pic"] = $_SESSION['id_karyawan'];
    }

    if (!empty($search)) {
        $sql .= " AND (
            s.srv_id LIKE :search
            OR s.tanggal LIKE :search
            OR s.jam LIKE :search
            OR c.netpay_id LIKE :search
            OR s.problem LIKE :search
            OR s.action LIKE :search
            OR s.part LIKE :search
            OR s.red_bef LIKE :search
            OR s.red_aft LIKE :search
            OR s.pic LIKE :search
            OR s.keterangan LIKE :search
        )";


        $params[':search'] = "%$search%";
    }

    if (!empty($date)) {
        $sql .= " AND s.tanggal LIKE :tanggal";
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
