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
    header("Location: " . BASE_URL . "pages/ikr/");
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
    header("Location: " . BASE_URL . "pages/ikr/");
    exit;
}

try {
    $pdo->beginTransaction();

    // Ambil data ikr
    $sql = "SELECT ikr_key, netpay_key, schedule_key FROM ikr WHERE ikr_key = :ikr_key";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ikr_key' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception("Data tidak ditemukan.");
    }

    // Update schedule
    $sql = "UPDATE schedules SET status = 'Pending' WHERE schedule_key = :schedule_key";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':schedule_key' => $row['schedule_key']]);

    // Update customer
    $sql = "UPDATE customers SET is_active = 'Inactive' WHERE netpay_key = :netpay_key";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':netpay_key' => $row['netpay_key']]);

    // Hapus dari IKR
    $sql = "DELETE FROM ikr WHERE ikr_key = :ikr_key";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ikr_key' => $row['ikr_key']]);

    if ($stmt->rowCount() === 0) {
        throw new Exception("Data IKR sudah dihapus sebelumnya.");
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

header("Location: " . BASE_URL . "pages/ikr/");
exit;
