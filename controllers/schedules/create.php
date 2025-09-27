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
    $schedule_id   = isset($_POST['schedule_id']) ? sanitize($_POST['schedule_id']) : null;
    $queue_id   = isset($_POST['queue_id']) ? sanitize($_POST['queue_id']) : null;
    $netpay_id   = isset($_POST['netpay_id']) ? sanitize($_POST['netpay_id']) : null;
    $tech_id   = isset($_POST['tech_id']) ? sanitize($_POST['tech_id']) : null;
    $date = isset($_POST['date']) ? sanitize($_POST['date']) : null;
    $time      = isset($_POST['time']) ? sanitize($_POST['time']) : null;
    $job_type  = isset($_POST['job_type']) ? sanitize($_POST['job_type']) : null;
    $catatan  = isset($_POST['catatan']) ? sanitize($_POST['catatan']) : null;

    // Pastikan semua data terisi
    if (!$schedule_id || !$queue_id || !$netpay_id || !$tech_id || !$date || !$time || !$job_type || !$catatan) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Schedule gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    }

    try {
        // Query insert dengan prepared statement
        $sql = "INSERT INTO schedules (schedule_id, netpay_id ,tech_id, `date`, `time`, job_type, queue_id, catatan) 
                VALUES (:schedule_id, :netpay_id, :tech_id, :date, :time, :job_type, :queue_id, :catatan)";
        $stmt = $pdo->prepare($sql);
        $scheduleSuccess = $stmt->execute([
            ':schedule_id' => $schedule_id,
            ':netpay_id' => $netpay_id,
            ':tech_id'     => $tech_id,
            ':date'        => $date,
            ':time'         => $time,
            ':job_type'    => $job_type,
            ':queue_id'     => $queue_id,
            ':catatan'     => $catatan
        ]);
        if ($scheduleSuccess) {
            $sql = "UPDATE queue_scheduling 
                        SET status = 'Accepted' 
                    WHERE queue_id = :queue_id";
            $stmt = $pdo->prepare($sql);
            $queueSuccess = $stmt->execute([":queue_id" => $queue_id]);
            if ($queueSuccess) {
                $_SESSION['alert'] = [
                    'icon' => 'success',
                    'title' => 'Selamat!',
                    'text' => 'Data schedule berhasil dibuat dan tersimpan dengan aman',
                    'button' => "Oke",
                    'style' => "success"
                ];
                header("Location: " . BASE_URL . "pages/schedule/");
                exit;
            }
        }
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
        'title' => 'Oops!',
        'text' => 'Gagal mengakses halaman!',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    header("Location: " . BASE_URL . "pages/schedule/");
    exit;
}
