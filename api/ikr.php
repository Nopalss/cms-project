<?php
require_once __DIR__ . "/../includes/config.php";

try {
    $search = $_POST['query']['generalSearch'] ?? '';
    $dateInput = $_POST['query']['date'] ?? '';

    // Konversi format tanggal (dari MM/DD/YYYY → YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('m/d/Y', $dateInput);
    $date    = $dateObj ? $dateObj->format('Y-m-d') : null;



    $sql = "SELECT i.*, c.netpay_id FROM ikr i LEFT JOIN customers c ON i.netpay_key = c.netpay_key WHERE 1=1";

    $params = [];
    if ($_SESSION['role'] == 'teknisi') {
        $sql .= ' AND pic = :pic';
        $params[":pic"] = $_SESSION['id_karyawan'];
    }

    if (!empty($search)) {
        $sql .= " AND (
                    i.ikr_id LIKE :search
                    OR c.netpay_id LIKE :search
                    OR i.group_ikr LIKE :search
                    OR i.ikr_an LIKE :search
                    OR i.alamat LIKE :search
                    OR i.rt LIKE :search
                    OR i.rw LIKE :search
                    OR i.desa LIKE :search
                    OR i.kec LIKE :search
                    OR i.kab LIKE :search
                    OR i.telp LIKE :search
                    OR i.sn LIKE :search
                    OR i.paket LIKE :search
                    OR i.type_ont LIKE :search
                    OR i.redaman LIKE :search
                    OR i.odp_no LIKE :search
                    OR i.odc_no LIKE :search
                    OR i.jc_no LIKE :search
                    OR i.mac_sebelum LIKE :search
                    OR i.mac_sesudah LIKE :search
                    OR i.odp LIKE :search
                    OR i.odc LIKE :search
                    OR i.enclosure LIKE :search
                    OR i.paket_no LIKE :search
                    OR i.created_at LIKE :search
                    OR i.updated_at LIKE :search
                    OR i.schedule_key LIKE :search
                )";

        $params[':search'] = "%$search%";
    }

    if (!empty($date)) {
        $sql .= " AND i.created_at LIKE :created_at";
        $params[':created_at'] = "%$date%";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $ikr = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "data" => $ikr
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
