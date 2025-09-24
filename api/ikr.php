<?php
require_once __DIR__ . "/../includes/config.php";

try {
    $search = $_POST['query']['generalSearch'] ?? '';
    $dateInput = $_POST['query']['date'] ?? '';
    $status = $_POST['query']['status'] ?? '';

    // Konversi format tanggal (dari MM/DD/YYYY → YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('m/d/Y', $dateInput);
    $date    = $dateObj ? $dateObj->format('Y-m-d') : null;

    $sql = "SELECT * FROM ikr WHERE 1=1";

    $params = [];

    if (!empty($search)) {
        $sql .= " AND (
                    ikr_id LIKE :search
                    OR netpay_id LIKE :search
                    OR group_ikr LIKE :search
                    OR ikr_an LIKE :search
                    OR alamat LIKE :search
                    OR rt LIKE :search
                    OR rw LIKE :search
                    OR desa LIKE :search
                    OR kec LIKE :search
                    OR kab LIKE :search
                    OR telp LIKE :search
                    OR sn LIKE :search
                    OR paket LIKE :search
                    OR type_ont LIKE :search
                    OR redaman LIKE :search
                    OR odp_no LIKE :search
                    OR odc_no LIKE :search
                    OR jc_no LIKE :search
                    OR mac_sebelum LIKE :search
                    OR mac_sesudah LIKE :search
                    OR odp LIKE :search
                    OR odc LIKE :search
                    OR enclosure LIKE :search
                    OR paket_no LIKE :search
                    OR created_at LIKE :search
                    OR updated_at LIKE :search
                    OR schedule_id LIKE :search
                )";

        $params[':search'] = "%$search%";
    }

    if (!empty($date)) {
        $sql .= " AND created_at LIKE :created_at";
        $params[':created_at'] = "%$date%";
    }
    if (!empty($status)) {
        $sql .= " AND q.status = :status";
        $params[':status'] = $status;
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
