<?php
require_once __DIR__ . "/../../../includes/config.php";
require_once __DIR__ . "/../../../helper/sanitize.php";
require_once __DIR__ . "/../../../helper/redirect.php";

// Set timezone

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil input & sanitasi
    $dismantle_id      = isset($_POST['dismantle_id']) ? sanitize($_POST['dismantle_id']) : null;
    $schedule_key       = isset($_POST['schedule_key']) ? sanitize($_POST['schedule_key']) : null;
    $netpay_key         = isset($_POST['netpay_key']) ? sanitize($_POST['netpay_key']) : null;
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
        'schedule_key'  => $schedule_key,
        'netpay_key'    => $netpay_key,
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
            redirect("pages/dismantle/");
        }
    }

    try {
        $pdo->beginTransaction();

        // Insert ke dismantle_reports
        $sql = "INSERT INTO dismantle_reports 
                (dismantle_id, schedule_key, netpay_key, tanggal, jam, alasan, action, part_removed, kondisi_perangkat, pic, keterangan) 
                VALUES 
                (:dismantle_id, :schedule_key, :netpay_key, :tanggal, :jam, :alasan, :action, :part_removed, :kondisi_perangkat, :pic, :keterangan)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':dismantle_id'      => $dismantle_id,
            ':schedule_key'       => $schedule_key,
            ':netpay_key'         => $netpay_key,
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
        $sql = "UPDATE schedules SET status = 'Done' WHERE schedule_key = :schedule_key";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':schedule_key' => $schedule_key]);

        $sql = "UPDATE customers SET is_active = 'Inactive' WHERE netpay_key = :netpay_key";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':netpay_key' => $netpay_key]);


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

    redirect("pages/dismantle/");
} else {
    $_SESSION['alert'] = [
        'icon' => 'warning',
        'title' => 'Oops!',
        'text' => 'Akses tidak valid.',
        'button' => "Oke",
        'style' => "warning"
    ];
    redirect("pages/dismantle/");
}
