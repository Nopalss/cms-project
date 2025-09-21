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
    $netpay_id   = isset($_POST['netpay_id']) ? sanitize($_POST['netpay_id']) : null;
    $tech_id   = isset($_POST['tech_id']) ? sanitize($_POST['tech_id']) : null;
    $date = isset($_POST['date']) ? sanitize($_POST['date']) : null;
    $time      = isset($_POST['time']) ? sanitize($_POST['time']) : null;
    $job_type  = isset($_POST['job_type']) ? sanitize($_POST['job_type']) : null;
    $status    = isset($_POST['status']) ? sanitize($_POST['status']) : null;

    // Validasi
    if (!$schedule_id || !$netpay_id  || !$tech_id || !$date || !$time || !$job_type || !$status) {
        $_SESSION['alert'] = [
            'icon' => 'danger',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Schedule gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/schedule");
        exit;
    }

    try {
        $sql = "UPDATE schedules 
                SET netpay_id= :netpay_id, 
                    tech_id = :tech_id, 
                    date = :date, 
                    time = :time, 
                    job_type = :job_type, 
                    status = :status
                WHERE schedule_id = :schedule_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':netpay_id'     => $netpay_id,
            ':tech_id'     => $tech_id,
            ':date'        => $date,
            ':time'        => $time,
            ':job_type'    => $job_type,
            ':status'      => $status,
            ':schedule_id' => $schedule_id
        ]);
        // if (!empty($issue_id)) {
        //     $sql = "UPDATE issues_report 
        //         SET status = 'Approved'
        //         WHERE issue_id = :issue_id";

        //     $stmt = $pdo->prepare($sql);
        //     $stmt->execute([
        //         ':issue_id' => $issue_id,
        //     ]);
        // }
        $_SESSION['alert'] = [
            'icon' => 'success',
            'title' => 'Selamat!',
            'text' => 'Data Schedule berhasil diperbarui',
            'button' => "Oke",
            'style' => "success"
        ];
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    } catch (PDOException $e) {
        echo $e;
        $_SESSION['alert'] = [
            'icon' => 'danger',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Silakan coba lagi nanti. Error: ' . $e->getMessage(),
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    }
} else {
    $_SESSION['alert'] = [
        'icon' => 'danger',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Gagal melakukan update, silakan coba lagi',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    header("Location: " . BASE_URL . "pages/schedule");
    exit;
}
