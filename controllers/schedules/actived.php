<?php

require_once __DIR__ . "/../../includes/config.php";
require_once __DIR__ . "/../../includes/check_password.php";

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
    header("Location: " . BASE_URL . "pages/schedule");
    exit;
}

$user = checkLogin($pdo, $username, $password);
if (!$user) {
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops!',
        'text' => 'Password salah.',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    header("Location: " . BASE_URL . "pages/schedule/");
    exit;
}

try {
    $sql = "UPDATE schedules SET status = 'Actived' WHERE schedule_id = :schedule_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':schedule_id' => $id
    ]);

    $_SESSION['alert'] = [
        'icon' => 'success',
        'title' => 'Sukses!',
        'text' => 'Status schedule berhasil diubah menjadi Actived.',
        'button' => 'Oke',
        'style' => 'success'
    ];
    header("Location: " . BASE_URL . "pages/schedule/");
    exit;
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Gagal mengubah status schedule.',
        'button' => 'Coba Lagi',
        'style' => 'danger'
    ];
    header("Location: " . BASE_URL . "pages/schedule/");
    exit;
}
