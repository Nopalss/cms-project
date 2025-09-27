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
    header("Location: " . BASE_URL . "pages/request/ikr/");
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
    header("Location: " . BASE_URL . "pages/request/ikr/");
    exit;
}


try {
    $pdo->beginTransaction();

    // Cari registrasi_id dari request_ikr
    $sql = "SELECT registrasi_id FROM request_ikr WHERE rikr_id = :id";
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
        header("Location: " . BASE_URL . "pages/request/ikr/");
        exit;
    }

    // Update status register jadi Unverified
    if (!empty($row['registrasi_id'])) {
        $sql = "UPDATE register 
                    SET is_verified = 'Unverified'
                    WHERE registrasi_id = :registrasi_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':registrasi_id' => $row['registrasi_id']]);
    }

    // Hapus dari queue_scheduling (anak)
    $sql = "DELETE FROM queue_scheduling WHERE request_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    // Hapus dari request_ikr (induk)
    $sql = "DELETE FROM request_ikr WHERE rikr_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    // Pastikan ada yang kehapus
    if ($stmt->rowCount() === 0) {
        $pdo->rollBack();
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'Oops!',
            'text' => 'Tidak ada data yang dihapus.',
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
        'icon' => 'error',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Silakan coba lagi nanti. Error: ' . $e->getMessage(),
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
}

header("Location: " . BASE_URL . "pages/request/ikr/");
exit;
