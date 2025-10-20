<?php

require_once __DIR__ . "/../../../includes/config.php";
require_once __DIR__ . "/../../../helper/redirect.php";
require_once __DIR__ . "/../../../helper/sanitize.php";

if (isset($_POST['submit'])) {
    $rm_id   = isset($_POST['rm_id']) ? sanitize($_POST['rm_id']) : null;
    $netpay_key   = isset($_POST['netpay_key']) ? sanitize($_POST['netpay_key']) : null;
    $type_issue   = isset($_POST['type_issue']) ? sanitize($_POST['type_issue']) : null;
    $deskripsi_issue   = isset($_POST['deskripsi_issue']) ? sanitize($_POST['deskripsi_issue']) : null;
    $request_by   = $_SESSION['username'];

    if (!$rm_id ||  !$netpay_key || !$type_issue || !$deskripsi_issue) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Request gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        redirect("pages/request/maintenance/");
    }

    $check = $pdo->prepare("SELECT 1 FROM customers WHERE netpay_key = :id AND is_active = 'Active'");
    $check->execute([':id' => $netpay_key]);
    if (!$check->fetch()) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops!',
            'text' => 'Customer tidak ditemukan atau tidak aktif.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        redirect("pages/request/maintenance/");
    }
    try {
        $pdo->beginTransaction();

        // Insert request
        $sql = "INSERT INTO request_maintenance (rm_id, netpay_key, type_issue, deskripsi_issue, request_by)
            VALUES (:rm_id, :netpay_key, :type_issue, :deskripsi_issue, :request_by)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":rm_id" => $rm_id,
            ":netpay_key" => $netpay_key,
            ":type_issue" => $type_issue,
            ":deskripsi_issue" => $deskripsi_issue,
            ":request_by" => $request_by
        ]);

        // Insert queue
        $queue_id = "Q" . date("YmdHis");
        $sql = "INSERT INTO queue_scheduling (queue_id, type_queue, request_id) 
            VALUES (:queue_id, :type_queue, :request_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':queue_id' => $queue_id,
            ':type_queue' => "Maintenance",
            ':request_id' => $rm_id
        ]);

        $pdo->commit();

        $_SESSION['alert'] = [
            'icon' => 'success',
            'title' => 'Selamat!',
            'text' => 'Pendaftaran Request sukses',
            'button' => "Oke",
            'style' => "success"
        ];
        redirect("pages/request/maintenance/");
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Silakan coba lagi nanti. Error: ' . $e->getMessage(),
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        redirect("pages/request/maintenance/");
    }
} else {
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Gagal melakukan request, silakan coba lagi',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    redirect("pages/request/maintenance/");
}
