<?php

require_once __DIR__ . "/../../../includes/config.php";

// var_dump($_POST);
if (isset($_POST['submit'])) {
    // Fungsi sanitize untuk cegah HTML Injection
    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    function validatePhone($phone)
    {
        // Hilangkan spasi, strip, atau karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Validasi nomor Indonesia
        // Harus mulai dengan 08, panjang 10–13 digit
        if (preg_match('/^08[0-9]{8,11}$/', $phone)) {
            return true;
        }

        // Alternatif: jika pakai kode negara (+62)
        if (preg_match('/^62[0-9]{9,12}$/', $phone)) {
            return true;
        }

        return false; // selain itu dianggap tidak valid
    }
    //     // Ambil & sanitasi data POST
    $netpay_old = isset($_POST['netpay_old']) ? sanitize($_POST['netpay_old']) : $null;
    $registrasi_id   = isset($_POST['registrasi_id']) ? sanitize($_POST['registrasi_id']) : null;
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
    if (!$name || !$phone || !$registrasi_id || !$paket_internet || !$date || !$time || !$perumahan || !$location || !validatePhone($phone) || !$rikr_id || !$netpay_kode  || !$netpay_id || !$catatan) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Update gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/request/ikr/");
        exit;
    }

    try {
        // query update request_ikr
        $pdo->beginTransaction();
        $sql = "UPDATE request_ikr 
                SET netpay_id = :netpay_id,
                    registrasi_id = :registrasi_id,
                    jadwal_pemasangan = :jadwal_pemasangan,
                    catatan = :catatan,
                    request_by = :request_by
                WHERE rikr_id = :rikr_id";

        $stmt = $pdo->prepare($sql);

        $rikr_success = $stmt->execute([
            ':netpay_id' => $netpay_id,
            ':registrasi_id' => $registrasi_id,
            ':jadwal_pemasangan' => $date . "T" . $time,
            ':catatan' => $catatan,
            ':request_by' => $_SESSION['username'],
            ':rikr_id' => $rikr_id,
        ]);


        // Query insert customers
        $sql = "UPDATE customers 
                    SET netpay_id = :netpay_id,
                        name = :name,
                        phone = :phone, 
                        paket_internet = :paket_internet, 
                        location = :location,
                        perumahan = :perumahan 
                    WHERE netpay_id = :netpay_old";
        $stmt = $pdo->prepare($sql);
        $customers_success = $stmt->execute([
            ':netpay_old' => $netpay_old,
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
        header("Location: " . BASE_URL . "pages/request/ikr/");
        exit;
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
        header("Location: " . BASE_URL . "pages/request/ikr/");
        exit;
    }
} else {
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Gagal melakukan update, silakan coba lagi',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    header("Location: " . BASE_URL . "pages/request/ikr/");
    exit;
}
