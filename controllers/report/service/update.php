<?php
require_once __DIR__ . "/../../../includes/config.php";

// Set timezone biar konsisten
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Fungsi sanitasi input
    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    // Ambil input & sanitasi
    $srv_id     = sanitize($_POST['srv_id'] ?? '');
    $netpay_id  = sanitize($_POST['netpay_id'] ?? '');
    $tanggal    = sanitize($_POST['tanggal'] ?? '');
    $jam        = sanitize($_POST['jam'] ?? '');
    $problem    = sanitize($_POST['problem'] ?? '');
    $action     = sanitize($_POST['action'] ?? '');
    $part       = sanitize($_POST['part'] ?? '');
    $red_bef    = sanitize($_POST['red_bef'] ?? '');
    $red_aft    = sanitize($_POST['red_aft'] ?? '');
    $pic        = sanitize($_POST['pic'] ?? '');
    $keterangan = sanitize($_POST['keterangan'] ?? '');
    $check = $pdo->prepare("SELECT COUNT(*) FROM service_reports WHERE srv_id = :srv_id");
    $check->execute([':srv_id' => $srv_id]);
    if ($check->fetchColumn() == 0) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops!',
            'text' => 'Service Report tidak ditemukan.',
            'button' => 'Coba Lagi',
            'style' => 'danger'
        ];
        header("Location: " . BASE_URL . "pages/service_report/");
        exit;
    }

    // Cek field wajib
    $requiredFields = [
        'srv_id'     => $srv_id,
        'netpay_id'  => $netpay_id,
        'tanggal'    => $tanggal,
        'jam'        => $jam,
        'problem'    => $problem,
        'action'     => $action,
        'part'       => $part,
        'red_bef'    => $red_bef,
        'red_aft'    => $red_aft,
        'pic'        => $pic,
        'keterangan' => $keterangan,
    ];

    foreach ($requiredFields as $field => $value) {
        if (empty($value)) {
            $_SESSION['alert'] = [
                'icon'   => 'error',
                'title'  => 'Oops!',
                'text'   => "Field <b>$field</b> tidak boleh kosong.",
                'button' => 'Coba Lagi',
                'style'  => 'danger'
            ];
            header("Location: " . BASE_URL . "pages/service_report/");
            exit;
        }
    }

    try {
        $pdo->beginTransaction();

        // Query update
        $sql = "UPDATE service_reports 
                SET tanggal = :tanggal,
                    jam = :jam,
                    netpay_id = :netpay_id,
                    problem = :problem,
                    action = :action,
                    part = :part,
                    red_bef = :red_bef,
                    red_aft = :red_aft,
                    pic = :pic,
                    keterangan = :keterangan
                WHERE srv_id = :srv_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':srv_id'     => $srv_id,
            ':tanggal'    => $tanggal,
            ':jam'        => $jam,
            ':netpay_id'  => $netpay_id,
            ':problem'    => $problem,
            ':action'     => $action,
            ':part'       => $part,
            ':red_bef'    => $red_bef,
            ':red_aft'    => $red_aft,
            ':pic'        => $pic,
            ':keterangan' => $keterangan,
        ]);

        $pdo->commit();

        if ($stmt->rowCount() > 0) {
            $_SESSION['alert'] = [
                'icon'   => 'success',
                'title'  => 'Berhasil!',
                'text'   => 'Service Report berhasil diperbarui.',
                'button' => 'Oke',
                'style'  => 'success'
            ];
        } else {
            $_SESSION['alert'] = [
                'icon'   => 'info',
                'title'  => 'Tidak Ada Perubahan',
                'text'   => 'Data tetap sama, tidak ada yang diperbarui.',
                'button' => 'Oke',
                'style'  => 'info'
            ];
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['alert'] = [
            'icon'   => 'error',
            'title'  => 'Error!',
            'text'   => 'Terjadi kesalahan saat memperbarui data. Error: ' . $e->getMessage(),
            'button' => 'Coba Lagi',
            'style'  => 'danger'
        ];
    }

    header("Location: " . BASE_URL . "pages/service_report/");
    exit;
} else {
    $_SESSION['alert'] = [
        'icon' => 'warning',
        'title' => 'Oops!',
        'text' => 'Akses tidak valid.',
        'button' => "Oke",
        'style' => "warning"
    ];
    header("Location: " . BASE_URL . "pages/service_report/");
    exit;
}
