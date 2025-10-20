<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../helper/sanitize.php';
require_once __DIR__ . '/../../helper/validatePhone.php';
require_once __DIR__ . '/../../helper/isDateValidTomorrow.php';

if (isset($_POST['submit'])) {

    // Ambil & sanitasi data POST
    $name   = isset($_POST['name']) ? sanitize($_POST['name']) : null;
    $phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : null;
    $paket_internet    = isset($_POST['paket_internet']) ? sanitize($_POST['paket_internet']) : null;
    $date  = isset($_POST['date']) ? sanitize($_POST['date']) : null;
    $time  = isset($_POST['time']) ? sanitize($_POST['time']) : null;
    $location  = isset($_POST['location']) ? sanitize($_POST['location']) : null;

    // Pastikan semua data terisi
    if (!$name || !$phone || !$paket_internet || !$date  || !$time || !$location || !validatePhone($phone) || !isDateValidTomorrow($date)) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Pendaftaran gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/registrasi");
        exit;
    }

    // Buat ID unik
    $registrasi_id = uniqid('REG');

    try {
        // Query insert dengan prepared statement
        $sql = "INSERT INTO register (registrasi_id, name, phone, paket_internet, date, time,  location) 
                VALUES (:registrasi_id, :name, :phone, :paket_internet, :date, :time, :location)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':registrasi_id' => $registrasi_id,
            ':name' => $name,
            ':phone' => $phone,
            ':paket_internet' => $paket_internet,
            ':date' => $date,
            ':time' => $time,
            ':location' => $location
        ]);
        $_SESSION['alert'] = [
            'icon' => 'success',
            'title' => 'Selamat!',
            'text' => 'Pendaftaran sukses. Tim kami akan segera menghubungi Anda',
            'button' => "Oke",
            'style' => "success"
        ];
        header("Location: " . BASE_URL . "pages/registrasi");
        exit;
    } catch (PDOException $e) {
        // echo $e;
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Silakan coba lagi nanti. Error: ' . $e->getMessage(),
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/registrasi");
        exit;
    }
} else {
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops! Ada yang Salah',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    header("Location: " . BASE_URL . "pages/registrasi");
    exit;
}
