<?php
require_once __DIR__ . "/../includes/config.php";

try {
    $search = $_POST['query']['generalSearch'] ?? '';
    $dateInput = $_POST['query']['date'] ?? '';
    $status = $_POST['query']['status'] ?? '';

    // Konversi format tanggal (dari MM/DD/YYYY → YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('m/d/Y', $dateInput);
    $date    = $dateObj ? $dateObj->format('Y-m-d') : null;

    $sql = "SELECT d.*, c.netpay_id FROM dismantle_reports d LEFT JOIN customers c ON d.netpay_key = c.netpay_key WHERE 1=1";

    $params = [];

    $params = [];
    if ($_SESSION['role'] == 'teknisi') {
        $sql .= ' AND d.pic = :pic';
        $params[":pic"] = $_SESSION['id_karyawan'];
    }

    if (!empty($search)) {
        $sql .= " AND (
                    d.dismantle_id LIKE :search
                    OR d.tanggal LIKE :search
                    OR d.jam LIKE :search
                    OR c.netpay_id LIKE :search
                    OR d.lokasi LIKE :search
                    OR d.alasan LIKE :search
                    OR d.action LIKE :search
                    OR d.part_removed LIKE :search
                    OR d.kondisi_perangkat LIKE :search
                    OR d.pic LIKE :search
                    OR d.keterangan LIKE :search
                )";
        $params[':search'] = "%$search%";
    }

    if (!empty($date)) {
        $sql .= " AND tanggal LIKE :tanggal";
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
