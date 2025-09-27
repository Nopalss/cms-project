<?php
require_once __DIR__ . "/../../includes/config.php";

var_dump($_POST);
if (isset($_POST['submit'])) {
    date_default_timezone_set('Asia/Jakarta');

    // Fungsi sanitasi
    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    // Ambil & sanitasi data
    $issue_id    = isset($_POST['issue_id']) ? sanitize($_POST['issue_id']) : null;
    $schedule_id = isset($_POST['schedule_id']) ? sanitize($_POST['schedule_id']) : null;
    $netpay_id   = isset($_POST['netpay_id']) ? sanitize($_POST['netpay_id']) : null;
    $tech_id     = isset($_POST['tech_id']) ? sanitize($_POST['tech_id']) : null;
    $date        = isset($_POST['date']) ? sanitize($_POST['date']) : null;
    $time        = isset($_POST['time']) ? sanitize($_POST['time']) : null;
    $job_type    = isset($_POST['job_type']) ? sanitize($_POST['job_type']) : null;
    $status      = isset($_POST['status']) ? sanitize($_POST['status']) : null;
    $catatan      = trim($_POST['catatan']);

    // Validasi dasar
    if (!$schedule_id || !$netpay_id || !$tech_id || !$date || !$time || !$job_type || !$status || !$catatan) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Schedule gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/schedule");
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Tentukan status schedule
        $statusToUpdate = !empty($issue_id) ? "Rescheduled" : $status;

        // Update schedules
        $sqlSchedule = "UPDATE schedules 
                            SET netpay_id = :netpay_id, 
                                tech_id   = :tech_id, 
                                date      = :date, 
                                time      = :time, 
                                job_type  = :job_type, 
                                status    = :status,
                                catatan    = :catatan
                            WHERE schedule_id = :schedule_id";
        $stmt = $pdo->prepare($sqlSchedule);
        $stmt->execute([
            ':netpay_id'     => $netpay_id,
            ':tech_id'       => $tech_id,
            ':date'          => $date,
            ':time'          => $time,
            ':job_type'      => $job_type,
            ':status'        => $statusToUpdate,
            ':catatan'   => $catatan,
            ':schedule_id'   => $schedule_id
        ]);


        // Update issues_report jika ada issue_id
        if (!empty($issue_id)) {
            $sqlIssue = "UPDATE issues_report 
                             SET status = 'Approved' 
                             WHERE issue_id = :issue_id";
            $stmtIssue = $pdo->prepare($sqlIssue);
            $stmtIssue->execute([':issue_id' => $issue_id]);
        }

        $pdo->commit();

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
        $pdo->rollBack();
        echo $e;
        error_log($e->getMessage()); // simpan log error, jangan tampilkan ke user
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Silakan coba lagi nanti.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/schedule/");
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
    header("Location: " . BASE_URL . "pages/schedule");
    exit;
}
