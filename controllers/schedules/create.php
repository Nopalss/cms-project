<?php
require_once __DIR__ . "/../../includes/config.php";

if (isset($_POST['submit'])) {
    date_default_timezone_set('Asia/Jakarta'); //Menyesuaikan waktu dengan tempat kita tinggal
    // Fungsi sanitize untuk cegah HTML Injection
    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    // Ambil & sanitasi data POST
    $tech_id   = isset($_POST['tech_id']) ? sanitize($_POST['tech_id']) : null;
    $dateInput = isset($_POST['date']) ? sanitize($_POST['date']) : null;
    $time      = isset($_POST['time']) ? sanitize($_POST['time']) : null;
    $job_type  = isset($_POST['job_type']) ? sanitize($_POST['job_type']) : null;
    $location  = isset($_POST['location']) ? sanitize($_POST['location']) : null;

    // Konversi format tanggal (dari MM/DD/YYYY → YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('m/d/Y', $dateInput);
    $date    = $dateObj ? $dateObj->format('Y-m-d') : null;
    // Pastikan semua data terisi
    if (!$tech_id || !$date || !$time || !$job_type || !$location) {
        $_SESSION['error'] = "Gagal menyimpan data, silakan coba lagi.";
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    }

    // Buat ID unik
    $schedule_id = 'S' . date("YmdHs");
    try {
        // Query insert dengan prepared statement
        $sql = "INSERT INTO schedules (schedule_id, tech_id, date, time, job_type, location) 
                VALUES (:schedule_id, :tech_id, :date, :time, :job_type, :location)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':schedule_id' => $schedule_id,
            ':tech_id'     => $tech_id,
            ':date'        => $date,
            ':time'         => $time,
            ':job_type'    => $job_type,
            ':location'    => $location
        ]);
        $_SESSION['success'] = "Data schedule berhasil dibuat dan tersimpan dengan aman";
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal menyimpan data, silakan coba lagi";
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    }
}
$_SESSION['error'] = "Gagal menyimpan data, silakan coba lagi";
header("Location: " . BASE_URL . "pages/schedule/");
exit;
