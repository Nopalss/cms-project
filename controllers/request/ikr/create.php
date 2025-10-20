<?php

require_once __DIR__ . "/../../../includes/config.php";
require_once __DIR__ . '/../../../helper/sanitize.php';
require_once __DIR__ . '/../../../helper/validatePhone.php';
require_once __DIR__ . '/../../../helper/redirect.php';
if (isset($_POST['submit'])) {

    // Ambil & sanitasi data POST
    $registrasi_key   = isset($_POST['registrasi_key']) ? sanitize($_POST['registrasi_key']) : null;
    $name   = isset($_POST['name']) ? sanitize($_POST['name']) : null;
    $phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : null;
    $paket_internet    = isset($_POST['paket_internet']) ? sanitize($_POST['paket_internet']) : null;
    $location  = isset($_POST['location']) ? sanitize($_POST['location']) : null;
    $rikr_id    = isset($_POST['rikr_id']) ? sanitize($_POST['rikr_id']) : null;
    $netpay_kode    = isset($_POST['netpay_kode']) ? sanitize($_POST['netpay_kode']) : null;
    $netpay_id    = isset($_POST['netpay_id']) ? sanitize($_POST['netpay_id']) : null;
    if (strlen($netpay_kode) != 3) {
        $netpay_id    = $netpay_kode . '0' . $netpay_id;
    } else {
        $netpay_id    = $netpay_kode . $netpay_id;
    }
    $time  = isset($_POST['time_pemasangan']) ? sanitize($_POST['time_pemasangan']) : null;
    $date  = isset($_POST['date_pemasangan']) ? sanitize($_POST['date_pemasangan']) : null;
    $catatan    = isset($_POST['catatan']) ? sanitize($_POST['catatan']) : null;
    $perumahan    = isset($_POST['perumahan']) ? sanitize($_POST['perumahan']) : null;

    // Pastikan semua data terisi
    if (!$name || !$phone  || !$registrasi_key || !$paket_internet || !$date || !$time || !$location || !$perumahan || !validatePhone($phone) || !$rikr_id || !$netpay_kode  || !$netpay_id || !$catatan) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Request gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        redirect("pages/request/ikr/");
    }

    try {
        // query insert request_ikr

        $pdo->beginTransaction();
        // Query insert customers
        $sql = "INSERT INTO customers (netpay_id, name, phone, paket_internet, location, perumahan) 
                VALUES (:netpay_id, :name, :phone, :paket_internet, :location, :perumahan)";
        $stmt = $pdo->prepare($sql);
        $customers_success = $stmt->execute([
            ':netpay_id' => $netpay_id,
            ':name' => $name,
            ':phone' => $phone,
            ':paket_internet' => $paket_internet,
            ':location' => $location,
            ':perumahan' => $perumahan
        ]);
        $netpay_key = $pdo->lastInsertId();

        $sql = "INSERT INTO request_ikr (rikr_id ,netpay_key, registrasi_key, jadwal_pemasangan, catatan, request_by) 
                VALUES (:rikr_id , :netpay_key, :registrasi_key, :jadwal_pemasangan, :catatan, :request_by)";
        $stmt = $pdo->prepare($sql);
        $rikr_success = $stmt->execute([
            ':rikr_id' => $rikr_id,
            ':netpay_key' => $netpay_key,
            ':registrasi_key' => $registrasi_key,
            ':jadwal_pemasangan' => $date . "T" . $time,
            ':catatan' => $catatan,
            ':request_by' => $_SESSION['username']
        ]);
        $queue_id = "Q" . date("YmdHis");
        $sql = "INSERT INTO queue_scheduling (queue_id, type_queue, request_id) 
                VALUES (:queue_id, :type_queue, :request_id)";
        $stmt = $pdo->prepare($sql);
        $queue_success = $stmt->execute([
            ':queue_id' => $queue_id,
            ':type_queue' => "Install",
            ':request_id' => $rikr_id
        ]);

        $sql = "UPDATE register 
                SET   is_verified = 'Verified'
                WHERE registrasi_key = :registrasi_key";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':registrasi_key' => $registrasi_key,
        ]);

        $pdo->commit();
        $_SESSION['alert'] = [
            'icon' => 'success',
            'title' => 'Selamat!',
            'text' => 'Pendaftaran Request sukses',
            'button' => "Oke",
            'style' => "success"
        ];
        redirect("pages/request/ikr/");
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("DB Error: " . $e->getMessage());
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Silakan coba lagi nanti.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        redirect("pages/request/ikr/");
    }
} else {
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Gagal melakukan request, silakan coba lagi',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    redirect("pages/request/ikr/");
}
