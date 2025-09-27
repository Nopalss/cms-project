<?php
require_once __DIR__ . "/../../includes/config.php";
require_once __DIR__ . "/../../includes/check_password.php";

if (isset($_POST['submit'])) {
    // Ambil data user dari database

    date_default_timezone_set('Asia/Jakarta'); // Sesuaikan timezone

    // Fungsi sanitize untuk cegah HTML Injection
    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
    $username = $_SESSION['username'] ?? null;
    $password = trim($_POST['password'] ?? '');
    if (!$username || !$password) {
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'Oops!',
            'text' => 'Data tidak lengkap.',
            'button' => "Coba Lagi",
            'style' => "warning"
        ];
        header("Location: " . BASE_URL . "pages/schedule");
        exit;
    }

    $user = checkLogin($pdo, $username, $password);
    if (!$user) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops!',
            'text' => 'Password salah.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    }
    // Ambil & sanitasi data POST
    $schedule_id = isset($_POST['schedule_id']) ? sanitize($_POST['schedule_id']) : null;
    $issue_type  = isset($_POST['issue_type']) ? sanitize($_POST['issue_type']) : null;
    $description = isset($_POST['description']) ? sanitize($_POST['description']) : null;
    $karyawan_id = $_SESSION['id_karyawan'] ?? null;

    // Pastikan semua data terisi
    if (!$schedule_id || !$karyawan_id || !$issue_type || !$description) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops!',
            'text' => 'Gagal menyimpan data, silakan coba lagi.',
            'button' => "Oke",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    }

    // Buat ID unik (kombinasi tanggal + uniqid biar aman)
    $issue_id = 'I' . date("YmdHs");

    try {
        // Query insert dengan prepared statement → tabel pakai plural
        $sql = "INSERT INTO issues_report (issue_id, schedule_id, reported_by, issue_type, description) 
                VALUES (:issue_id, :schedule_id, :reported_by, :issue_type, :description)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':issue_id'    => $issue_id,
            ':schedule_id' => $schedule_id,
            ':reported_by' => $karyawan_id,
            ':issue_type'  => $issue_type,
            ':description' => $description,
        ]);

        $_SESSION['alert'] = [
            'icon' => 'success',
            'title' => 'Selamat!',
            'text' => 'Issue Report berhasil dibuat dan tersimpan dengan aman.',
            'button' => "Oke",
            'style' => "success"
        ];
    } catch (PDOException $e) {
        error_log("DB Error: " . $e->getMessage()); // simpan ke error log server
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops!',
            'text' => 'Gagal menyimpan data, silakan coba lagi.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
    }
} else {
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Akses tidak valid!',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
}
header("Location: " . BASE_URL . "pages/schedule/");
exit;
