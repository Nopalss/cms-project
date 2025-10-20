<?php

require_once __DIR__ . "/../../../includes/config.php";
require_once __DIR__ . "/../../../helper/redirect.php";
require_once __DIR__ . "/../../../helper/sanitize.php";

if (isset($_POST['submit'])) {
    $rd_key   = isset($_POST['rd_key']) ? sanitize($_POST['rd_key']) : null;
    $rd_id   = isset($_POST['rd_id']) ? sanitize($_POST['rd_id']) : null;
    $type_dismantle   = isset($_POST['type_dismantle']) ? sanitize($_POST['type_dismantle']) : null;
    $deskripsi_dismantle   = isset($_POST['deskripsi_dismantle']) ? sanitize($_POST['deskripsi_dismantle']) : null;
    $request_by   = $_SESSION['username'];

    if (!$rd_key || !$type_dismantle || !$deskripsi_dismantle) {
        $_SESSION['alert'] = [
            'icon' => 'danger',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Update gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        redirect("pages/request/dismantle/");
    }

    try {
        $pdo->beginTransaction();

        // Update request
        $sql = "UPDATE request_dismantle 
                SET type_dismantle = :type_dismantle, 
                    deskripsi_dismantle = :deskripsi_dismantle
                WHERE rd_key = :rd_key";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":rd_key" => $rd_key,
            ":type_dismantle" => $type_dismantle,
            ":deskripsi_dismantle" => $deskripsi_dismantle,
        ]);

        $pdo->commit();

        $_SESSION['alert'] = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Data Request berhasil diperbarui',
            'button' => "Oke",
            'style' => "success"
        ];
        redirect("pages/request/dismantle/");
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log($e->getMessage()); // simpan di error log server
        $_SESSION['alert'] = [
            'icon' => 'danger',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Silakan coba lagi nanti.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        redirect("pages/request/dismantle/");
    }
} else {
    $_SESSION['alert'] = [
        'icon' => 'danger',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Gagal melakukan update, silakan coba lagi',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    redirect("pages/request/dismantle/");
}
