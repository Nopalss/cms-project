<?php
require_once __DIR__ . "/../includes/config.php";

try {
    $search = $_POST['query']['generalSearch'] ?? '';
    $issue_type = $_POST['query']['issue_type'] ?? '';
    $reported_by = $_POST['query']['tech_id'] ?? '';
    $dateInput = $_POST['query']['date'] ?? '';
    // Konversi format tanggal (dari MM/DD/YYYY → YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('m/d/Y', $dateInput);
    $date    = $dateObj ? $dateObj->format('Y-m-d') : null;
    $sql = "SELECT 
                i.issue_id,
                i.schedule_id,
                i.reported_by,
                i.issue_type,
                i.description,
                i.created_at,
                i.status AS issue_status,
                s.date, 
                s.time, 
                s.location, 
                s.job_type, 
                s.status AS schedule_status,
                t.name AS technician_name
            FROM issues_report i
            LEFT JOIN schedules s ON i.schedule_id = s.schedule_id
            LEFT JOIN technician t ON i.reported_by = t.tech_id
            WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $sql .= " AND (
                        i.issue_id LIKE :search
                        OR i.schedule_id LIKE :search
                        OR i.reported_by LIKE :search
                        OR i.issue_type LIKE :search
                        OR i.description LIKE :search
                        OR i.created_at LIKE :search
                        OR i.status LIKE :search
                        OR s.date LIKE :search
                        OR s.time LIKE :search
                        OR s.location LIKE :search
                        OR s.job_type LIKE :search
                        OR s.status LIKE :search
                        OR t.name LIKE :search
                    )
    ";
        $params[':search'] = "%$search%";
    }


    if (!empty($issue_type)) {
        $sql .= " AND i.issue_type = :issue_type";
        $params[':issue_type'] = $issue_type;
    }

    if (!empty($reported_by)) {
        $sql .= " AND i.reported_by = :reported_by";
        $params[':reported_by'] = $reported_by;
    }
    if (!empty($date)) {
        $sql .= " AND DATE(i.created_at) = :search_date";
        $params[':search_date'] = $date;
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $issue_report = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "data" => $issue_report
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
