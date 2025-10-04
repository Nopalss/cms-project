<?php
require_once __DIR__ . '/../../includes/config.php';

if (isset($_POST['submit'])) {
    date_default_timezone_set('Asia/Jakarta');

    // Fungsi sanitize untuk cegah HTML Injection
    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    function isDateValidTomorrow($dateInput)
    {
        // Pastikan format Y-m-d
        $d = DateTime::createFromFormat('Y-m-d', $dateInput);
        if (!$d || $d->format('Y-m-d') !== $dateInput) {
            return false; // Format salah
        }

        $tomorrow = new DateTime('tomorrow');
        return $d >= $tomorrow;
    }

    function validatePhone($phone)
    {
        // Hilangkan spasi, strip, atau karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Validasi nomor Indonesia
        if (preg_match('/^08[0-9]{8,11}$/', $phone)) {
            return true;
        }

        if (preg_match('/^62[0-9]{9,12}$/', $phone)) {
            return true;
        }

        return false;
    }

    // Ambil & sanitasi data POST
    $registrasi_id = isset($_POST['registrasi_id']) ? sanitize($_POST['registrasi_id']) : null;
    $name          = isset($_POST['name']) ? sanitize($_POST['name']) : null;
    $phone         = isset($_POST['phone']) ? sanitize($_POST['phone']) : null;
    $paket_internet = isset($_POST['paket_internet']) ? sanitize($_POST['paket_internet']) : null;
    $date  = isset($_POST['date']) ? sanitize($_POST['date']) : null;
    $time  = isset($_POST['time']) ? sanitize($_POST['time']) : null;
    $location      = isset($_POST['location']) ? trim($_POST['location']) : null;

    // Pastikan semua data terisi
    if (!$name || !$phone || !$paket_internet || !$date  || !$time || !$location || !validatePhone($phone) || !isDateValidTomorrow($date)) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Update gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/registrasi/");
        exit;
    }

    try {
        // Query update dengan prepared statement
        $sql = "UPDATE register 
                SET name = :name,
                    phone = :phone,
                    paket_internet = :paket_internet,
                    date = :date,
                    time = :time,
                    location = :location
                WHERE registrasi_id = :registrasi_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':registrasi_id' => $registrasi_id,
            ':name' => $name,
            ':phone' => $phone,
            ':paket_internet' => $paket_internet,
            ':date' => $date,
            ':time' => $time,
            ':location' => $location,
        ]);
        $_SESSION['alert'] = [
            'icon' => 'success',
            'title' => 'Selamat!',
            'text' => 'Data registrasi berhasil diperbarui',
            'button' => "Oke",
            'style' => "success"
        ];
        header("Location: " . BASE_URL . "pages/registrasi/");
        exit;
    } catch (PDOException $e) {
        error_log("DB Error: " . $e->getMessage());
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Silakan coba lagi nanti.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/registrasi/");
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
    header("Location: " . BASE_URL . "pages/registrasi/");
    exit;
}
