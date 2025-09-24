<?php
require_once __DIR__ . "/../../../includes/config.php";
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    $dismantle_id      = isset($_POST['dismantle_id']) ? sanitize($_POST['dismantle_id']) : null;
    $netpay_id         = isset($_POST['netpay_id']) ? sanitize($_POST['netpay_id']) : null;
    $tanggal           = isset($_POST['tanggal']) ? sanitize($_POST['tanggal']) : null;
    $jam               = isset($_POST['jam']) ? sanitize($_POST['jam']) : null;
    $alasan            = isset($_POST['alasan']) ? sanitize($_POST['alasan']) : null;
    $action            = isset($_POST['action']) ? sanitize($_POST['action']) : null;
    $part_removed      = isset($_POST['part_removed']) ? sanitize($_POST['part_removed']) : null;
    $kondisi_perangkat = isset($_POST['kondisi_perangkat']) ? sanitize($_POST['kondisi_perangkat']) : null;
    $pic               = isset($_POST['pic']) ? sanitize($_POST['pic']) : null;
    $keterangan        = isset($_POST['keterangan']) ? sanitize($_POST['keterangan']) : null;

    $requiredFields = [
        'dismantle_id' => $dismantle_id,
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

        // Update dismantle_reports
        $sql = "UPDATE dismantle_reports 
                SET netpay_id = :netpay_id, 
                    tanggal = :tanggal, 
                    jam = :jam, 
                    alasan = :alasan, 
                    action = :action, 
                    part_removed = :part_removed, 
                    kondisi_perangkat = :kondisi_perangkat, 
                    pic = :pic, 
                    keterangan = :keterangan
                WHERE dismantle_id = :dismantle_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':dismantle_id'      => $dismantle_id,
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
        $pdo->commit();

        $_SESSION['alert'] = [
            'icon'   => 'success',
            'title'  => 'Berhasil!',
            'text'   => 'Dismantle Report berhasil diperbarui.',
            'button' => 'Oke',
            'style'  => 'success'
        ];
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['alert'] = [
            'icon'   => 'danger',
            'title'  => 'Error!',
            'text'   => 'Gagal update data. Error: ' . $e->getMessage(),
            'button' => 'Coba Lagi',
            'style'  => 'danger'
        ];
    }

    header("Location: " . BASE_URL . "pages/dismantle/");
    exit;
}
