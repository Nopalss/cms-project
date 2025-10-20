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
    $pdo->beginTransaction();

    // Pastikan schedule ada
    $sql = "SELECT schedule_id, schedule_key, queue_key FROM schedules WHERE schedule_key = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'Oops!',
            'text' => 'Data tidak ditemukan.',
            'button' => "Oke",
            'style' => "warning"
        ];
        redirect("pages/schedule/");
    }

    // Update queue_scheduling → set status Pending
    $sql = "UPDATE queue_scheduling 
                SET status = 'Pending'
                WHERE queue_key = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $row['queue_key']]);

    // Hapus schedule
    $sql = "DELETE FROM schedules WHERE schedule_key = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) {
        if ($pdo->inTransaction()) $pdo->rollBack();
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
    if ($pdo->inTransaction()) $pdo->rollBack();
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Silakan coba lagi nanti.',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    error_log($e->getMessage());
}

redirect("pages/schedule/");
