<?php

require_once __DIR__ . "/../../../includes/config.php";

var_dump($_POST);
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
    // Ambil & sanitasi data POST
    $registrasi_id   = isset($_POST['registrasi_id']) ? sanitize($_POST['registrasi_id']) : null;
    $name   = isset($_POST['name']) ? sanitize($_POST['name']) : null;
    $phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : null;
    $paket_internet    = isset($_POST['paket_internet']) ? sanitize($_POST['paket_internet']) : null;
    $is_verified    = isset($_POST['is_verified']) ? sanitize($_POST['is_verified']) : null;
    $request_schedule  = isset($_POST['request_schedule']) ? sanitize($_POST['request_schedule']) : null;
    $location  = isset($_POST['location']) ? sanitize($_POST['location']) : null;
    $rikr_id    = isset($_POST['rikr_id']) ? sanitize($_POST['rikr_id']) : null;
    $netpay_kode    = isset($_POST['netpay_kode']) ? sanitize($_POST['netpay_kode']) : null;
    $netpay_id    = isset($_POST['netpay_id']) ? sanitize($_POST['netpay_id']) : null;
    $netpay_id    = $netpay_kode . $netpay_id;
    $jadwal_pemasangan  = isset($_POST['jadwal_pemasangan']) ? sanitize($_POST['jadwal_pemasangan']) : null;
    $catatan    = isset($_POST['catatan']) ? sanitize($_POST['catatan']) : null;

    // Pastikan semua data terisi
    if (!$name || !$phone || !$paket_internet || !$request_schedule || !$location || !validatePhone($phone) || !$is_verified || !$rikr_id || !$netpay_kode  || !$netpay_id || !$catatan ||  $jadwal_pemasangan != $request_schedule) {
        $_SESSION['error'] = "Request gagal. Pastikan semua data sudah diisi dengan benar";
        header("Location: " . BASE_URL . "pages/request/ikr/");
        exit;
    }

    try {
        // query insert request_ikr
        $sql = "INSERT INTO request_ikr (rikr_id ,netpay_id, registrasi_id, jadwal_pemasangan, catatan, request_by) 
                VALUES (:rikr_id , :netpay_id, :registrasi_id, :jadwal_pemasangan, :catatan, :request_by)";
        $stmt = $pdo->prepare($sql);
        $rikr_success = $stmt->execute([
            ':rikr_id' => $rikr_id,
            ':netpay_id' => $netpay_id,
            ':registrasi_id' => $registrasi_id,
            ':jadwal_pemasangan' => $jadwal_pemasangan,
            ':catatan' => $catatan,
            ':request_by' => $_SESSION['username']
        ]);

        if ($rikr_success) {
            // Query insert customers
            $sql = "INSERT INTO customers (netpay_id, name, phone, paket_internet, location) 
                VALUES (:netpay_id, :name, :phone, :paket_internet, :location)";
            $stmt = $pdo->prepare($sql);
            $customers_success = $stmt->execute([
                ':netpay_id' => $netpay_id,
                ':name' => $name,
                ':phone' => $phone,
                ':paket_internet' => $paket_internet,
                ':location' => $location,

            ]);
            //  cek kalo success
            if ($customers_success) {
                // Query insert queue schedules

                $queue_id = "Q" . date("YmdHs");
                $sql = "INSERT INTO queue_scheduling (queue_id, type_queue, request_id) 
                VALUES (:queue_id, :type_queue, :request_id)";
                $stmt = $pdo->prepare($sql);
                $queue_success = $stmt->execute([
                    ':queue_id' => $queue_id,
                    ':type_queue' => "Install",
                    ':request_id' => $rikr_id,
                ]);
                if ($queue_success) {
                    // Query update dengan prepared statement
                    $sql = "UPDATE register 
                            SET name = :name,
                                phone = :phone,
                                paket_internet = :paket_internet,
                                request_schedule = :request_schedule,
                                location = :location,
                                is_verified = :is_verified
                            WHERE registrasi_id = :registrasi_id";

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':registrasi_id' => $registrasi_id,
                        ':name' => $name,
                        ':phone' => $phone,
                        ':paket_internet' => $paket_internet,
                        ':request_schedule' => $request_schedule,
                        ':location' => $location,
                        ':is_verified' => $is_verified
                    ]);
                    $_SESSION['success'] = "Pendaftaran Request sukses";
                    header("Location: " . BASE_URL . "pages/request/ikr/");
                    exit;
                }
            }
        }
    } catch (PDOException $e) {
        // echo $e;
        $_SESSION['error'] = "Gagal menyimpan data: " . $e->getMessage();
        header("Location: " . BASE_URL . "pages/request/ikr/");
        exit;
    }
} else {
    $_SESSION['error'] = "Gagal melakukan request, silakan coba lagi";
    header("Location: " . BASE_URL . "pages/request/ikr/");
    exit;
}
