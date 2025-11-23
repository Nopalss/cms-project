<?php

require_once __DIR__ . "/../../../includes/config.php";
require_once __DIR__ . "/../../../helper/redirect.php";
require_once __DIR__ . "/../../../helper/sanitize.php";

if (isset($_POST['submit'])) {
    $rd_id   = isset($_POST['rd_id']) ? sanitize($_POST['rd_id']) : null;
    $netpay_key   = isset($_POST['netpay_key']) ? sanitize($_POST['netpay_key']) : null;
    $type_dismantle   = isset($_POST['type_dismantle']) ? sanitize($_POST['type_dismantle']) : null;
    $deskripsi_dismantle   = isset($_POST['deskripsi_dismantle']) ? sanitize($_POST['deskripsi_dismantle']) : null;
    $request_by   = $_SESSION['username'];
    $netpay_id   = isset($_POST['netpay_id']) ? sanitize($_POST['netpay_id']) : null;
    $name   = isset($_POST['name']) ? sanitize($_POST['name']) : null;
    $phone   = isset($_POST['phone']) ? sanitize($_POST['phone']) : null;
    $is_active   = isset($_POST['is_active']) ? sanitize($_POST['is_active']) : null;
    $perumahan   = isset($_POST['perumahan']) ? sanitize($_POST['perumahan']) : null;
    $location   = isset($_POST['location']) ? sanitize($_POST['location']) : null;
    $paket_internet   = isset($_POST['paket_internet']) ? sanitize($_POST['paket_internet']) : null;

    $check = $pdo->prepare("SELECT COUNT(*) FROM customers WHERE netpay_id = :netpay_id");
    $check->execute([':netpay_id' => $netpay_id]);
    $exists = $check->fetchColumn();

    if (!$exists) {
        // Belum ada, insert baru
        $sql = "INSERT INTO customers (netpay_key, netpay_id, name, phone, paket_internet, location, perumahan)
            VALUES (:netpay_key, :netpay_id, :name, :phone, :paket_internet, :location, :perumahan)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":netpay_key" => $netpay_key,
            ':netpay_id' => $netpay_id,
            ':name' => $name,
            ':phone' => $phone,
            ':paket_internet' => $paket_internet,
            ':location' => $location,
            ':perumahan' => $perumahan
        ]);
    }

    if (!$rd_id  || !$netpay_key || !$type_dismantle || !$deskripsi_dismantle) {
        $_SESSION['alert'] = [
            'icon' => 'danger',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Request gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        redirect("pages/request/dismantle/");
    }

    try {
        $pdo->beginTransaction();

        // Insert request
        $sql = "INSERT INTO request_dismantle (rd_id, netpay_key, type_dismantle, deskripsi_dismantle, request_by)
            VALUES (:rd_id, :netpay_key, :type_dismantle, :deskripsi_dismantle, :request_by)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":rd_id" => $rd_id,
            ":netpay_key" => $netpay_key,
            ":type_dismantle" => $type_dismantle,
            ":deskripsi_dismantle" => $deskripsi_dismantle,
            ":request_by" => $request_by
        ]);
        // Insert queue
        $queue_id = "Q" . date("YmdHis");
        $sql = "INSERT INTO queue_scheduling (queue_id, type_queue, request_id) 
            VALUES (:queue_id, :type_queue, :request_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':queue_id' => $queue_id,
            ':type_queue' => "Dismantle",
            ':request_id' => $rd_id
        ]);

        $pdo->commit();

        $_SESSION['alert'] = [
            'icon' => 'success',
            'title' => 'Selamat!',
            'text' => 'Pendaftaran Request sukses',
            'button' => "Oke",
            'style' => "success"
        ];
        redirect("pages/request/dismantle/");
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['alert'] = [
            'icon' => 'danger',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Terjadi kesalahan pada server. Silakan coba lagi nanti',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        redirect("pages/request/dismantle/");
    }
} else {
    $_SESSION['alert'] = [
        'icon' => 'danger',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Gagal melakukan request, silakan coba lagi',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    redirect("pages/request/dismantle/");
}
