<?php

require_once __DIR__ . "/../../../includes/config.php";
require_once __DIR__ . '/../../../helper/sanitize.php';
require_once __DIR__ . '/../../../helper/validatePhone.php';
require_once __DIR__ . '/../../../helper/redirect.php';

if (isset($_POST['submit'])) {
    //     // Ambil & sanitasi data POST
    $rikr_key    = isset($_POST['rikr_key']) ? sanitize($_POST['rikr_key']) : null;
    $netpay_key = isset($_POST['netpay_key']) ? sanitize($_POST['netpay_key']) : null;
    $registrasi_key   = isset($_POST['registrasi_key']) ? sanitize($_POST['registrasi_key']) : null;
    $name   = isset($_POST['name']) ? sanitize($_POST['name']) : null;
    $phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : null;
    $paket_internet    = isset($_POST['paket_internet']) ? sanitize($_POST['paket_internet']) : null;
    $location  = isset($_POST['location']) ? sanitize($_POST['location']) : null;
    $rikr_id    = isset($_POST['rikr_id']) ? sanitize($_POST['rikr_id']) : null;
    $netpay_kode    = isset($_POST['netpay_kode']) ? sanitize($_POST['netpay_kode']) : null;
    $netpay_id    = isset($_POST['netpay_id']) ? sanitize($_POST['netpay_id']) : null;
    $netpay_id    = $netpay_kode . $netpay_id;
    $time  = isset($_POST['time_pemasangan']) ? sanitize($_POST['time_pemasangan']) : null;
    $date  = isset($_POST['date_pemasangan']) ? sanitize($_POST['date_pemasangan']) : null;
    $catatan    = isset($_POST['catatan']) ? sanitize($_POST['catatan']) : null;
    $perumahan    = isset($_POST['perumahan']) ? sanitize($_POST['perumahan']) : null;

    // Pastikan semua data terisi
    if (!$name || !$phone || !$registrasi_key || !$netpay_key || !$rikr_key || !$paket_internet || !$date || !$time || !$perumahan || !$location || !validatePhone($phone)  || !$netpay_kode  || !$netpay_id || !$catatan) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Update gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        redirect("pages/request/ikr/");
    }

    try {
        // query update request_ikr
        $pdo->beginTransaction();
        $sql = "UPDATE request_ikr 
                SET jadwal_pemasangan = :jadwal_pemasangan,
                    catatan = :catatan
                WHERE rikr_key = :rikr_key";

        $stmt = $pdo->prepare($sql);

        $rikr_success = $stmt->execute([
            ':jadwal_pemasangan' => $date . "T" . $time,
            ':catatan' => $catatan,
            ':rikr_key' => $rikr_key
        ]);

        // Query update customers
        $sql = "UPDATE customers 
                    SET netpay_id = :netpay_id,
                        name = :name,
                        phone = :phone, 
                        paket_internet = :paket_internet, 
                        location = :location,
                        perumahan = :perumahan 
                    WHERE netpay_key = :netpay_key";
        $stmt = $pdo->prepare($sql);
        $customers_success = $stmt->execute([
            ':netpay_key' => $netpay_key,
            ':netpay_id' => $netpay_id,
            ':name' => $name,
            ':phone' => $phone,
            ':paket_internet' => $paket_internet,
            ':location' => $location,
            ':perumahan' => $perumahan
        ]);
        $pdo->commit();

        $_SESSION['alert'] = [
            'icon' => 'success',
            'title' => 'Selamat!',
            'text' => 'Data Request berhasil diperbarui',
            'button' => "Oke",
            'style' => "success"
        ];
        redirect("pages/request/ikr/");
    } catch (PDOException $e) {
        // echo $e;
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
        'text' => 'Gagal melakukan update, silakan coba lagi',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    redirect("pages/request/ikr/");
}
