<?php
require_once __DIR__ . "/../../../includes/config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "DELETE FROM request_ikr WHERE rikr_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['alert'] = [
            'icon' => 'success',
            'title' => 'Selamat!',
            'text' => 'Data berhasil dihapus.',
            'button' => "Oke",
            'style' => "success"
        ];
    } catch (PDOException $e) {
        $_SESSION['alert'] = [
            'icon' => 'danger',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Silakan coba lagi nanti. Error: ' . $e->getMessage(),
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
    }
}


// balikin ke halaman schedule
header("Location: " . BASE_URL . "pages/request/ikr/");
exit;
