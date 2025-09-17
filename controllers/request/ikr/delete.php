<?php
require_once __DIR__ . "/../../../includes/config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "DELETE FROM request_ikr WHERE rikr_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['success'] = "Data request berhasil dihapus";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal menghapus data";
    }
}

// balikin ke halaman schedule
header("Location: " . BASE_URL . "pages/request/ikr/");
exit;
