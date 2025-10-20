<?php

require_once __DIR__ . "/../../../includes/config.php";
require_once __DIR__ . "/../../../helper/sanitize.php";
require_once __DIR__ . "/../../../helper/redirect.php";

if (isset($_POST['submit'])) {
    $rm_key   = isset($_POST['rm_key']) ? (int)$_POST['rm_key'] : null;
    $type_issue   = isset($_POST['type_issue']) ? sanitize($_POST['type_issue']) : null;
    $deskripsi_issue   = isset($_POST['deskripsi_issue']) ? sanitize($_POST['deskripsi_issue']) : null;

    if (!$rm_key || !$type_issue || !$deskripsi_issue) {
        $_SESSION['alert'] = [
            'icon' => 'danger',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Update gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        redirect("pages/request/maintenance/");
    }

    try {
        $pdo->beginTransaction();

        // Update request
        $sql = "UPDATE request_maintenance 
                SET type_issue = :type_issue, 
                    deskripsi_issue = :deskripsi_issue
                WHERE rm_key = :rm_key";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":rm_key" => $rm_key,
            ":type_issue" => $type_issue,
            ":deskripsi_issue" => $deskripsi_issue,
        ]);

        $pdo->commit();

        $_SESSION['alert'] = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Data Request berhasil diperbarui',
            'button' => "Oke",
            'style' => "success"
        ];
        redirect("pages/request/maintenance/");
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['alert'] = [
            'icon' => 'danger',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Silakan coba lagi nanti. Error: ' . $e->getMessage(),
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        redirect("pages/request/maintenance/");
    }
} else {
    $_SESSION['alert'] = [
        'icon' => 'danger',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Gagal melakukan update, silakan coba lagi',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    redirect("pages/request/maintenance/");
}
