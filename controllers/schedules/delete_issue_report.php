<?php
require_once __DIR__ . "/../../includes/config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $sql = "DELETE FROM issues_report WHERE issue_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['alert'] = [
                'icon' => 'success',
                'title' => 'Selamat!',
                'text' => 'Data berhasil dihapus.',
                'button' => "Oke",
                'style' => "success"
            ];
        } else {
            $_SESSION['alert'] = [
                'icon' => 'warning',
                'title' => 'Oops!',
                'text' => 'Data tidak ditemukan atau sudah dihapus.',
                'button' => "Oke",
                'style' => "warning"
            ];
        }
    } catch (PDOException $e) {
        error_log($e->getMessage()); // simpan di error log server
        $_SESSION['alert'] = [
            'icon' => 'danger',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Silakan coba lagi nanti.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
    }
}
// balikin ke halaman schedule
header("Location: " . BASE_URL . "pages/schedule/");
exit;
