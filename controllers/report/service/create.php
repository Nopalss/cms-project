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
    $schedule_key     = sanitize($_POST['schedule_key'] ?? '');
    $netpay_key  = sanitize($_POST['netpay_key'] ?? '');
    $tanggal    = sanitize($_POST['tanggal'] ?? '');
    $jam        = sanitize($_POST['jam'] ?? '');
    $problem    = sanitize($_POST['problem'] ?? '');
    $action     = sanitize($_POST['action'] ?? '');
    $part       = sanitize($_POST['part'] ?? '');
    $red_bef    = sanitize($_POST['red_bef'] ?? '');
    $red_aft    = sanitize($_POST['red_aft'] ?? '');
    $pic        = sanitize($_POST['pic'] ?? '');
    $keterangan = sanitize($_POST['keterangan'] ?? '');

    // Cek field wajib
    $requiredFields = [
        'srv_id'     => $srv_id,
        'netpay_key'  => $netpay_key,
        'tanggal'    => $tanggal,
        'jam'        => $jam,
        'problem'    => $problem,
        'action'     => $action,
        'part'       => $part,
        'red_bef'    => $red_bef,
        'red_aft'    => $red_aft,
        'pic'        => $pic,
        'keterangan' => $keterangan,
        'schedule_key' => $schedule_key,
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
            header("Location: " . BASE_URL . "pages/service_reports/");
            exit;
        }
    }

    try {
        $checkSrv = $pdo->prepare("SELECT COUNT(*) FROM service_reports WHERE srv_key = :srv_key");
        $checkSrv->execute([':srv_key' => $srv_key]);
        if ($checkSrv->fetchColumn() > 0) {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Duplikat!',
                'text' => 'Service Report ID sudah digunakan.',
                'button' => 'Coba Lagi',
                'style' => 'danger'
            ];
            header("Location: " . BASE_URL . "pages/service_reports/");
            exit;
        }

        $pdo->beginTransaction();

        // Query insert
        $sql = "INSERT INTO service_reports 
                (srv_id, tanggal, jam, netpay_key, problem, action, part, red_bef, red_aft, pic, keterangan, schedule_key) 
                VALUES 
                (:srv_id, :tanggal, :jam, :netpay_key, :problem, :action, :part, :red_bef, :red_aft, :pic, :keterangan, :schedule_key)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':srv_id'     => $srv_id,
            ':tanggal'    => $tanggal,
            ':jam'        => $jam,
            ':netpay_key'  => $netpay_key,
            ':problem'    => $problem,
            ':action'     => $action,
            ':part'       => $part,
            ':red_bef'    => $red_bef,
            ':red_aft'    => $red_aft,
            ':pic'        => $pic,
            ':keterangan' => $keterangan,
            ':schedule_key' => $schedule_key
        ]);
        // Update schedules
        $sql = "UPDATE schedules SET status = 'Done' WHERE schedule_key = :schedule_key";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':schedule_key' => $schedule_key]);

        $pdo->commit();

        $_SESSION['alert'] = [
            'icon'   => 'success',
            'title'  => 'Berhasil!',
            'text'   => 'Service Report berhasil disimpan.',
            'button' => 'Oke',
            'style'  => 'success'
        ];
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("DB Error (service_reports): " . $e->getMessage());
        $_SESSION['alert'] = [
            'icon'   => 'error',
            'title'  => 'Error!',
            'text'   => 'Terjadi kesalahan saat menyimpan data.',
            'button' => 'Coba Lagi',
            'style'  => 'danger'
        ];
    }

    header("Location: " . BASE_URL . "pages/service_reports/");
    exit;
} else {
    $_SESSION['alert'] = [
        'icon' => 'warning',
        'title' => 'Oops!',
        'text' => 'Akses tidak valid.',
        'button' => "Oke",
        'style' => "warning"
    ];
    header("Location: " . BASE_URL . "pages/service_reports/");
    exit;
}
