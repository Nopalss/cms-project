<?php
require_once __DIR__ . "/../includes/config.php";
header('Content-Type: application/json; charset=utf-8');

try {
    // Ambil semua data pending
    $sql = "SELECT * FROM queue_scheduling WHERE status = 'Pending'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Grouping manual di PHP
    $install = [];
    $maintenance = [];
    $dismantle = [];

    foreach ($rows as $row) {
        if ($row['type_queue'] === 'Install') {
            $install[] = $row;
        } elseif ($row['type_queue'] === 'Maintenance') {
            $maintenance[] = $row;
        } elseif ($row['type_queue'] === 'Dismantle') {
            $dismantle[] = $row;
        }
    }

    echo json_encode([
        "status"      => "success",
        "total"       => count($rows), // total pending
        "data"        => $rows,
        "install"     => $install,
        "maintenance" => $maintenance,
        "dismantle"   => $dismantle,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => $e->getMessage()
    ]);
}
