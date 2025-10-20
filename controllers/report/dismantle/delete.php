<?php
require_once __DIR__ . "/../../../includes/config.php";
require_once __DIR__ . "/../../../includes/check_password.php";
require_once __DIR__ . "/../../../helper/redirect.php";

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
    redirect("pages/dismantle/");
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
    redirect("pages/dismantle/");
}

try {
    $pdo->beginTransaction();

    // Ambil data ikr
    $sql = "SELECT dismantle_id, dismantle_key, schedule_key, netpay_key FROM dismantle_reports WHERE dismantle_key = :dismantle_key";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':dismantle_key' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception("Data tidak ditemukan.");
    }

    // Update schedule
    $sql = "UPDATE schedules SET status = 'Pending' WHERE schedule_key = :schedule_key";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':schedule_key' => $row['schedule_key']]);

    $sql = "UPDATE customers SET is_active = 'Active' WHERE netpay_key = :netpay_key";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':netpay_key' => $row['netpay_key']]);

    // Hapus dari IKR
    $sql = "DELETE FROM dismantle_reports WHERE dismantle_key = :dismantle_key";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':dismantle_key' => $row['dismantle_key']]);

    if ($stmt->rowCount() === 0) {
        throw new Exception("Data dismantle sudah dihapus sebelumnya.");
    }

    $pdo->commit();
    $_SESSION['alert'] = [
        'icon' => 'success',
        'title' => 'Selamat!',
        'text' => 'Data berhasil dihapus.',
        'button' => "Oke",
        'style' => "success"
    ];
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['alert'] = [
        'icon' => 'warning',
        'title' => 'Oops!',
        'text' => $e->getMessage(),
        'button' => "Oke",
        'style' => "warning"
    ];
} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Silakan coba lagi nanti. Error: ' . $e->getMessage(),
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
}

redirect("pages/dismantle/");
