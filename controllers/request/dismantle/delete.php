<?php
require_once __DIR__ . "/../../../includes/config.php";
require_once __DIR__ . "/../../../includes/check_password.php";

$username = $_SESSION['username'] ?? null;
$password = trim($_POST['password'] ?? '');
$id       = $_POST['id'] ?? null;

if (!$username || !$password || !$id) {
    $_SESSION['alert'] = [
        'icon' => 'warning',
        'title' => 'Oops!',
        'text' => 'Data tidak lengkap.',
        'button' => "Coba Lagi",
        'style' => "warning"
    ];
    header("Location: " . BASE_URL . "pages/request/dismantle/");
    exit;
}

$user = checkLogin($pdo, $username, $password);
if (!$user) {
    $_SESSION['alert'] = [
        'icon' => 'danger',
        'title' => 'Oops!',
        'text' => 'Password salah.',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    header("Location: " . BASE_URL . "pages/request/dismantle/");
    exit;
}
try {
    $pdo->beginTransaction();

    // Pastikan request_maintenance ada
    $sql = "SELECT rd_key, rd_id FROM request_dismantle WHERE rd_key = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $pdo->rollBack();
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'Oops!',
            'text' => 'Data tidak ditemukan.',
            'button' => "Oke",
            'style' => "warning"
        ];
        header("Location: " . BASE_URL . "pages/request/dismantle/");
        exit;
    }

    // Hapus dulu dari queue_scheduling (anak)
    $sql = "DELETE FROM queue_scheduling WHERE request_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $row['rd_id']]);

    // Hapus dari request_maintenance (induk)
    $sql = "DELETE FROM request_dismantle WHERE rd_key = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) {
        // Tidak ada baris terhapus → mungkin sudah dihapus sebelumnya
        $pdo->rollBack();
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'Oops!',
            'text' => 'Data tidak bisa dihapus (mungkin sudah dihapus sebelumnya).',
            'button' => "Oke",
            'style' => "warning"
        ];
    } else {
        $pdo->commit();
        $_SESSION['alert'] = [
            'icon' => 'success',
            'title' => 'Selamat!',
            'text' => 'Data berhasil dihapus.',
            'button' => "Oke",
            'style' => "success"
        ];
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['alert'] = [
        'icon' => 'danger',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Silakan coba lagi nanti. Error: ' . $e->getMessage(),
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
}
header("Location: " . BASE_URL . "pages/request/dismantle/");
exit;
