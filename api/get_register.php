<?php
require_once __DIR__ . "/../includes/config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT registrasi_id, name, phone, paket_internet, is_verified, location, request_schedule 
            FROM register 
            WHERE registrasi_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo json_encode($row);
    } else {
        echo json_encode([]);
    }
}
