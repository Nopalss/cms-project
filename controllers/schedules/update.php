<?php
require_once __DIR__ . "/../../includes/config.php";

if (isset($_POST['submit'])) {
    date_default_timezone_set('Asia/Jakarta');

    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    // Ambil & sanitasi data
    $schedule_id = $_POST['schedule_id'] ?? null;
    $tech_id     = $_POST['tech_id'] ?? null;
    $dateInput   = $_POST['date'] ?? null;
    $time        = $_POST['time'] ?? null;
    $job_type    = $_POST['job_type'] ?? null;
    $location    = $_POST['location'] ?? null;
    $status      = $_POST['status'] ?? null;

    // Konversi tanggal
    $dateObj = DateTime::createFromFormat('m/d/Y', sanitize($dateInput));
    $date    = $dateObj ? $dateObj->format('Y-m-d') : null;

    // Validasi
    if (!$schedule_id || !$tech_id || !$date || !$time || !$job_type || !$location || !$status) {
        $_SESSION['error'] = "Gagal menyimpan data, silakan coba lagi.";
        header("Location: " . BASE_URL . "pages/schedule");
        exit;
    }

    try {
        $sql = "UPDATE schedules 
                SET tech_id = :tech_id, 
                    date = :date, 
                    time = :time, 
                    job_type = :job_type, 
                    status = :status, 
                    location = :location
                WHERE schedule_id = :schedule_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':tech_id'     => sanitize($tech_id),
            ':date'        => $date,
            ':time'        => sanitize($time),
            ':job_type'    => sanitize($job_type),
            ':status'      => sanitize($status),
            ':location'    => sanitize($location),
            ':schedule_id' => sanitize($schedule_id)
        ]);

        $_SESSION['success'] = "Data schedule berhasil di Update dan tersimpan dengan aman";
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal!!. silakan coba lagi.";
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    }
} else {
    $_SESSION['error'] = "Gagal Update data, silakan coba lagi.";
    header("Location: " . BASE_URL . "pages/schedule");
    exit;
}
