<?php
require_once __DIR__ . "/../../../includes/config.php";

// Set timezone
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Fungsi sanitasi input
    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    // Ambil input & sanitasi
    $dismantle_id      = isset($_POST['dismantle_id']) ? sanitize($_POST['dismantle_id']) : null;
    $schedule_id       = isset($_POST['schedule_id']) ? sanitize($_POST['schedule_id']) : null;
    $netpay_id         = isset($_POST['netpay_id']) ? sanitize($_POST['netpay_id']) : null;
    $tanggal           = isset($_POST['tanggal']) ? sanitize($_POST['tanggal']) : null;
    $jam               = isset($_POST['jam']) ? sanitize($_POST['jam']) : null;
    $alasan            = isset($_POST['alasan']) ? sanitize($_POST['alasan']) : null;
    $action            = isset($_POST['action']) ? sanitize($_POST['action']) : null;
    $part_removed      = isset($_POST['part_removed']) ? sanitize($_POST['part_removed']) : null;
    $kondisi_perangkat = isset($_POST['kondisi_perangkat']) ? sanitize($_POST['kondisi_perangkat']) : null;
    $pic               = isset($_POST['pic']) ? sanitize($_POST['pic']) : null;
    $keterangan               = isset($_POST['keterangan']) ? sanitize($_POST['keterangan']) : null;
    // Validasi field wajib
    $requiredFields = [
        'dismantle_id' => $dismantle_id,
        'schedule_id'  => $schedule_id,
        'netpay_id'    => $netpay_id,
        'tanggal'      => $tanggal,
        'jam'          => $jam,
        'alasan'       => $alasan,
        'action'         => $action,
        'part_removed'         => $part_removed,
        'kondisi_perangkat'         => $kondisi_perangkat,
        'pic'          => $pic,
        'keterangan'   => $keterangan,
    ];

    foreach ($requiredFields as $field => $value) {
        if (empty($value)) {
            $_SESSION['alert'] = [
                'icon'   => 'danger',
                'title'  => 'Oops!',
                'text'   => "Field <b>$field</b> tidak boleh kosong.",
                'button' => 'Coba Lagi',
                'style'  => 'danger'
            ];
            header("Location: " . BASE_URL . "pages/dismantle/");
            exit;
        }
    }

    try {
        $pdo->beginTransaction();

        // Insert ke dismantle_reports
        $sql = "INSERT INTO dismantle_reports 
                (dismantle_id, schedule_id, netpay_id, tanggal, jam, alasan, action, part_removed, kondisi_perangkat, pic, keterangan) 
                VALUES 
                (:dismantle_id, :schedule_id, :netpay_id, :tanggal, :jam, :alasan, :action, :part_removed, :kondisi_perangkat, :pic, :keterangan)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':dismantle_id'      => $dismantle_id,
            ':schedule_id'       => $schedule_id,
            ':netpay_id'         => $netpay_id,
            ':tanggal'           => $tanggal,
            ':jam'               => $jam,
            ':alasan'            => $alasan,
            ':action'            => $action,
            ':part_removed'      => $part_removed,
            ':kondisi_perangkat' => $kondisi_perangkat,
            ':pic'               => $pic,
            ':keterangan'        => $keterangan,
        ]);

        // Update status schedule → Done
        $sql = "UPDATE schedules SET status = 'Done' WHERE schedule_id = :schedule_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':schedule_id' => $schedule_id]);

        $sql = "UPDATE customers SET is_active = 'Inactive' WHERE netpay_id = :netpay_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':netpay_id' => $netpay_id]);


        $pdo->commit();

        $_SESSION['alert'] = [
            'icon'   => 'success',
            'title'  => 'Berhasil!',
            'text'   => 'Dismantle Report berhasil disimpan.',
            'button' => 'Oke',
            'style'  => 'success'
        ];
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("DB Error dismantle: " . $e->getMessage());
        $_SESSION['alert'] = [
            'icon'   => 'danger',
            'title'  => 'Error!',
            'text'   => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.',
            'button' => 'Coba Lagi',
            'style'  => 'danger'
        ];
    }

    header("Location: " . BASE_URL . "pages/dismantle/");
    exit;
} else {
    $_SESSION['alert'] = [
        'icon' => 'warning',
        'title' => 'Oops!',
        'text' => 'Akses tidak valid.',
        'button' => "Oke",
        'style' => "warning"
    ];
    header("Location: " . BASE_URL . "pages/dismantle/");
    exit;
}
