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
    header("Location: " . BASE_URL . "pages/dismantle/");
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
    header("Location: " . BASE_URL . "pages/dismantle/");
    exit;
}

try {
    $pdo->beginTransaction();

    // Ambil data ikr
    $sql = "SELECT dismantle_id, schedule_id, netpay_id FROM dismantle_reports WHERE dismantle_id = :dismantle_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':dismantle_id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception("Data tidak ditemukan.");
    }

    // Update schedule
    $sql = "UPDATE schedules SET status = 'Pending' WHERE schedule_id = :schedule_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':schedule_id' => $row['schedule_id']]);

    $sql = "UPDATE customers SET is_active = 'Active' WHERE netpay_id = :netpay_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':netpay_id' => $row['netpay_id']]);

    // Hapus dari IKR
    $sql = "DELETE FROM dismantle_reports WHERE dismantle_id = :dismantle_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':dismantle_id' => $row['dismantle_id']]);

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

header("Location: " . BASE_URL . "pages/dismantle/");
exit;
