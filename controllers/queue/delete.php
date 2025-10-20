<?php
require_once __DIR__ . "/../../includes/config.php";
require_once __DIR__ . "/../../includes/check_password.php";
require_once __DIR__ . "/../../helper/redirect.php";

$username = $_SESSION['username'] ?? null;
$password = trim($_POST['password'] ?? '');
$id       = $_POST['id'] ?? null;

if (!$username || !$password || !$id) {
    $_SESSION['alert'] = [
        'icon'  => 'warning',
        'title' => 'Oops!',
        'text'  => 'Data tidak lengkap.',
        'button' => "Coba Lagi",
        'style' => "warning"
    ];
    redirect("pages/queue/");
}

// 🔒 Verifikasi password admin
$user = checkLogin($pdo, $username, $password);
if (!$user) {
    $_SESSION['alert'] = [
        'icon'  => 'error',
        'title' => 'Oops!',
        'text'  => 'Password salah.',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    redirect("pages/queue/");
}

try {
    $pdo->beginTransaction();

    // Ambil data queue
    $sql = "SELECT *
            FROM queue_scheduling 
            WHERE queue_key = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $queue = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$queue) {
        throw new Exception('Data queue tidak ditemukan.');
    }

    switch ($queue['type_queue']) {
        case "Install":
            $stmt = $pdo->prepare("SELECT * FROM request_ikr WHERE rikr_id = :id");
            $stmt->execute([':id' => $queue['request_id']]);
            $rikr = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$rikr) throw new Exception('Data request IKR tidak ditemukan.');

            // Update register -> Unverified
            if (!empty($rikr['registrasi_id'])) {
                $pdo->prepare("UPDATE register 
                               SET is_verified='Unverified' 
                               WHERE registrasi_id = :id")
                    ->execute([':id' => $rikr['registrasi_id']]);
            }

            $pdo->prepare("DELETE FROM request_ikr WHERE rikr_id = :id")
                ->execute([':id' => $rikr['rikr_id']]);
            break;

        case "Maintenance":
            $stmt = $pdo->prepare("SELECT * FROM request_maintenance WHERE rm_id = :id");
            $stmt->execute([':id' => $queue['request_id']]);
            $rm = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$rm) throw new Exception('Data request Maintenance tidak ditemukan.');

            $pdo->prepare("DELETE FROM request_maintenance WHERE rm_id = :id")
                ->execute([':id' => $rm['rm_id']]);
            break;

        case "Dismantle":
            $stmt = $pdo->prepare("SELECT * FROM request_dismantle WHERE rd_id = :id");
            $stmt->execute([':id' => $queue['request_id']]);
            $rd = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$rd) throw new Exception('Data request Dismantle tidak ditemukan.');

            $pdo->prepare("DELETE FROM request_dismantle WHERE rd_id = :id")
                ->execute([':id' => $rd['rd_id']]);
            break;
    }

    // Hapus queue_scheduling
    $stmt = $pdo->prepare("DELETE FROM queue_scheduling WHERE queue_key = :id");
    $stmt->execute([':id' => $queue['queue_key']]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('Tidak ada data queue yang dihapus.');
    }

    $pdo->commit();
    $_SESSION['alert'] = [
        'icon'  => 'success',
        'title' => 'Selamat!',
        'text'  => 'Data berhasil dihapus.',
        'button' => "Oke",
        'style' => "success"
    ];
} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Queue deletion error: " . $e->getMessage());
    $_SESSION['alert'] = [
        'icon'  => 'error',
        'title' => 'Oops! Ada yang Salah',
        'text'  => $e->getMessage(),
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
}

redirect("pages/queue/");
