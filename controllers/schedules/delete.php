<?php
require_once __DIR__ . "/../../includes/config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "DELETE FROM schedules WHERE schedule_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['success'] = "Data schedule berhasil dihapus";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal menghapus data: " . $e->getMessage();
    }
}

// balikin ke halaman schedule
header("Location: " . BASE_URL . "pages/schedule/");
exit;
