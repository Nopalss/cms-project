<?php

require_once __DIR__ . "/../../includes/config.php";
require_once __DIR__ . "/../../includes/check_password.php";
require_once __DIR__ . "/../../helper/redirect.php";

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
    redirect("pages/schedule");
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
    redirect("pages/schedule/");
}

try {
    $sql = "UPDATE schedules SET status = 'Actived' WHERE schedule_key = :schedule_key";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':schedule_key' => $id
    ]);

    $_SESSION['alert'] = [
        'icon' => 'success',
        'title' => 'Sukses!',
        'text' => 'Status schedule berhasil diubah menjadi Actived.',
        'button' => 'Oke',
        'style' => 'success'
    ];
    redirect("pages/schedule/");
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Gagal mengubah status schedule.',
        'button' => 'Coba Lagi',
        'style' => 'danger'
    ];
    redirect("pages/schedule/");
}
