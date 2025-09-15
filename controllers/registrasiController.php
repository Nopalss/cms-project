<?php
require_once __DIR__ . '/../includes/config.php';

if (isset($_POST['submit'])) {
    date_default_timezone_set('Asia/Jakarta');
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
    $name   = isset($_POST['name']) ? sanitize($_POST['name']) : null;
    $phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : null;
    $paket_internet    = isset($_POST['paket_internet']) ? sanitize($_POST['paket_internet']) : null;
    $request_schedule  = isset($_POST['request_schedule']) ? sanitize($_POST['request_schedule']) : null;
    $location  = isset($_POST['location']) ? sanitize($_POST['location']) : null;
    // Konversi format tanggal (dari MM/DD/YYYY → YYYY-MM-DD)

    // Pastikan semua data terisi
    if (!$name || !$phone || !$paket_internet || !$request_schedule || !$location || !validatePhone($phone)) {
        $_SESSION['error'] = "Pendaftaran gagal. Pastikan semua data sudah diisi dengan benar";
        header("Location: " . BASE_URL . "registration.php");
        exit;
    }


    // Buat ID unik
    $registrasi_id = date("YmdHs");
    try {
        // Query insert dengan prepared statement
        $sql = "INSERT INTO register (registrasi_id, name, phone, paket_internet, request_schedule, location) 
                VALUES (:registrasi_id, :name, :phone, :paket_internet, :request_schedule, :location)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':registrasi_id' => $registrasi_id,
            ':name' => $name,
            ':phone' => $phone,
            ':paket_internet' => $paket_internet,
            ':request_schedule' => $request_schedule,
            ':location' => $location
        ]);
        $_SESSION['success'] = "Pendaftaran sukses. Tim kami akan segera menghubungi Anda.";
        header("Location: " . BASE_URL . "registration.php");
        exit;
    } catch (PDOException $e) {
        // echo $e;
        $_SESSION['error'] = "Gagal menyimpan data, silakan coba lagi";
        header("Location: " . BASE_URL . "registration.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Gagal melakukan registrasi, silakan coba lagi";
    header("Location: " . BASE_URL . "registration.php");
    exit;
}
