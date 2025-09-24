<?php
require_once __DIR__ . "/../../includes/config.php";

if (isset($_POST['submit'])) {
    // Ambil data user dari database

    date_default_timezone_set('Asia/Jakarta'); // Sesuaikan timezone

    // Fungsi sanitize untuk cegah HTML Injection
    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    // Ambil & sanitasi data POST
    $schedule_id = isset($_POST['schedule_id']) ? sanitize($_POST['schedule_id']) : null;
    $issue_type  = isset($_POST['issue_type']) ? sanitize($_POST['issue_type']) : null;
    $description = isset($_POST['description']) ? sanitize($_POST['description']) : null;
    $password = isset($_POST['password']) ? sanitize($_POST['password']) : null;
    $karyawan_id = $_SESSION['id_karyawan'] ?? null;

    // Pastikan semua data terisi
    if (!$schedule_id || !$karyawan_id || !$issue_type || !$description || !$password) {
        $_SESSION['alert'] = [
            'icon' => 'danger',
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
    $username = $_SESSION['username'];

    $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // if ($user && password_verify($password, $user['password'])) {
    if ($user && $password == $user['password']) {

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
                'icon' => 'danger',
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
            'text' => 'Password salah!',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
    }
    header("Location: " . BASE_URL . "pages/schedule/");
    exit;
} else {
    $_SESSION['alert'] = [
        'icon' => 'warning',
        'title' => 'Oops!',
        'text' => 'Form tidak valid.',
        'button' => "Coba Lagi",
        'style' => "warning"
    ];
    header("Location: " . BASE_URL . "pages/schedule/");
    exit;
}
