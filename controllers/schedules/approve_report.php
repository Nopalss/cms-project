<?php

require_once __DIR__ . "/../../includes/config.php";

try {
    $schedule_id = $_GET["scheduleId"] ?? null;
    $issue_id = $_GET["id"] ?? null;

    // ini ubah status tabel issues_report menjadi approved
    $sql = "UPDATE issues_report 
                SET status = 'Approved'
                WHERE issue_id = :issue_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':issue_id' => $issue_id,
    ]);

    // ini ubah status tabel schedules menjadi cancel
    $sql = "UPDATE schedules
                SET status = 'Cancelled'
                WHERE schedule_id = :schedule_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':schedule_id' => $schedule_id]);
    $_SESSION['success'] = "Data Schedules sudah diubah";
    header("Location: " . BASE_URL . "pages/schedule/");
    exit;
} catch (PDOException $e) {
    $_SESSION['error'] = "Gagal menyimpan data, silakan coba lagi";
    header("Location: " . BASE_URL . "pages/schedule/");
    exit;
}
