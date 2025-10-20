<?php
require_once __DIR__ . "/../../../includes/config.php";
require_once __DIR__ . "/../../../helper/sanitize.php";
require_once __DIR__ . "/../../../helper/redirect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dismantle_key      = isset($_POST['dismantle_key']) ? sanitize($_POST['dismantle_key']) : null;
    $tanggal           = isset($_POST['tanggal']) ? sanitize($_POST['tanggal']) : null;
    $jam               = isset($_POST['jam']) ? sanitize($_POST['jam']) : null;
    $alasan            = isset($_POST['alasan']) ? sanitize($_POST['alasan']) : null;
    $action            = isset($_POST['action']) ? sanitize($_POST['action']) : null;
    $part_removed      = isset($_POST['part_removed']) ? sanitize($_POST['part_removed']) : null;
    $kondisi_perangkat = isset($_POST['kondisi_perangkat']) ? sanitize($_POST['kondisi_perangkat']) : null;
    $keterangan        = isset($_POST['keterangan']) ? sanitize($_POST['keterangan']) : null;

    $requiredFields = [
        'dismantle_key' => $dismantle_key,
        'tanggal'      => $tanggal,
        'jam'          => $jam,
        'alasan'       => $alasan,
        'action'         => $action,
        'part_removed'         => $part_removed,
        'kondisi_perangkat'         => $kondisi_perangkat,
        'keterangan'   => $keterangan,
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
            redirect("pages/dismantle/");
        }
    }

    try {
        $pdo->beginTransaction();

        // Update dismantle_reports
        $sql = "UPDATE dismantle_reports 
                SET tanggal = :tanggal, 
                    jam = :jam, 
                    alasan = :alasan, 
                    action = :action, 
                    part_removed = :part_removed, 
                    kondisi_perangkat = :kondisi_perangkat, 
                    keterangan = :keterangan
                WHERE dismantle_key = :dismantle_key";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':dismantle_key'      => $dismantle_key,
            ':tanggal'           => $tanggal,
            ':jam'               => $jam,
            ':alasan'            => $alasan,
            ':action'            => $action,
            ':part_removed'      => $part_removed,
            ':kondisi_perangkat' => $kondisi_perangkat,
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
        error_log("Update dismantle error: " . $e->getMessage());
        $_SESSION['alert'] = [
            'icon'   => 'error',
            'title'  => 'Error!',
            'text'   => 'Gagal update data. Silakan coba lagi.',
            'button' => 'Coba Lagi',
            'style'  => 'danger'
        ];
    }
    redirect("pages/dismantle/");
}
