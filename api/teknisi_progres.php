<?php
require_once __DIR__ . "/../includes/config.php";

try {

    $search    = $_POST['query']['generalSearch'] ?? '';
    $tech      = $_POST['query']['tech_id'] ?? '';
    $dateInput = $_POST['query']['date'] ?? '';

    // Konversi format tanggal (MM/DD/YYYY → YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('m/d/Y', $dateInput);
    $date    = $dateObj ? $dateObj->format('Y-m-d') : null;

    // ============================
    //      SQL BARU
    // ============================
    $sql = "
        SELECT 
            t.tech_id,
            t.name AS technician_name,
            SUM(CASE WHEN s.job_type = 'Instalasi'  THEN 1 ELSE 0 END) AS total_instalasi,
            SUM(CASE WHEN s.job_type = 'Maintenance' THEN 1 ELSE 0 END) AS total_maintenance,
            SUM(CASE WHEN s.job_type = 'Dismantle'   THEN 1 ELSE 0 END) AS total_dismantle,
            COUNT(s.schedule_id) AS total_tugas,
            SUM(CASE WHEN s.status = 'Done' THEN 1 ELSE 0 END) AS total_done
        FROM technician t
        LEFT JOIN schedules s 
            ON t.tech_id = s.tech_id
            AND (s.date = :date OR (:date IS NULL AND s.date = CURDATE()))
        WHERE 1=1
    ";

    $params = [
        ':date' => $date
    ];

    // Filter individu tech
    if (!empty($tech)) {
        $sql .= " AND t.tech_id = :tech_id";
        $params[':tech_id'] = $tech;
    }

    // Filter search
    if (!empty($search)) {
        $sql .= " AND (
            t.name LIKE :search
            OR t.tech_id LIKE :search
        )";
        $params[':search'] = "%$search%";
    }

    $sql .= " GROUP BY t.tech_id, t.name ORDER BY t.name ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mapping output
    $data = array_map(function ($r) {
        return [
            "tech_id"         => $r["tech_id"],
            "technician_name" => $r["technician_name"],
            "total_instalasi" => (int) $r["total_instalasi"],
            "total_maintenance" => (int) $r["total_maintenance"],
            "total_dismantle"  => (int) $r["total_dismantle"],
            "total_tugas"       => (int) $r["total_tugas"],
            "total_done"        => (int) $r["total_done"]
        ];
    }, $rows);

    echo json_encode(["data" => $data]);
} catch (PDOException $e) {
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
