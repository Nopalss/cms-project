<?php
require_once __DIR__ . "/../../includes/config.php";

if (isset($_POST['submit'])) {
    date_default_timezone_set('Asia/Jakarta');

    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    // Ambil & sanitasi data
    $schedule_id = isset($_POST['schedule_id']) ? sanitize($_POST['schedule_id']) : null;
    $issue_id = isset($_POST['issue_id']) ? sanitize($_POST['issue_id']) : null;
    $tech_id   = isset($_POST['tech_id']) ? sanitize($_POST['tech_id']) : null;
    $date = isset($_POST['date']) ? sanitize($_POST['date']) : null;
    $time      = isset($_POST['time']) ? sanitize($_POST['time']) : null;
    $job_type  = isset($_POST['job_type']) ? sanitize($_POST['job_type']) : null;
    $location  = isset($_POST['location']) ? sanitize($_POST['location']) : null;
    $status    = isset($_POST['status']) ? sanitize($_POST['status']) : null;
    echo $issue_id;
    // Konversi tanggal


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
            ':tech_id'     => $tech_id,
            ':date'        => $date,
            ':time'        => $time,
            ':job_type'    => $job_type,
            ':status'      => $status,
            ':location'    => $location,
            ':schedule_id' => $schedule_id
        ]);
        if (!empty($issue_id)) {
            $sql = "UPDATE issues_report 
                SET status = 'Approved'
                WHERE issue_id = :issue_id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':issue_id' => $issue_id,
            ]);
        }
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
