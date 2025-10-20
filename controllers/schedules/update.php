<?php
require_once __DIR__ . "/../../includes/config.php";
require_once __DIR__ . "/../../helper/sanitize.php";
require_once __DIR__ . "/../../helper/redirect.php";

if (isset($_POST['submit'])) {
    // Ambil & sanitasi data
    $issue_id    = isset($_POST['issue_id']) ? sanitize($_POST['issue_id']) : null;
    $schedule_key = isset($_POST['schedule_key']) ? sanitize($_POST['schedule_key']) : null;
    $netpay_key   = isset($_POST['netpay_key']) ? sanitize($_POST['netpay_key']) : null;
    $tech_id     = isset($_POST['tech_id']) ? sanitize($_POST['tech_id']) : null;
    $date        = isset($_POST['date']) ? sanitize($_POST['date']) : null;
    $time        = isset($_POST['time']) ? sanitize($_POST['time']) : null;
    $job_type    = isset($_POST['job_type']) ? sanitize($_POST['job_type']) : null;
    $status      = isset($_POST['status']) ? sanitize($_POST['status']) : null;
    $catatan = isset($_POST['catatan']) ? sanitize($_POST['catatan']) : null;

    // Validasi dasar
    if (!$schedule_key || !$netpay_key || !$tech_id || !$date || !$time || !$job_type || !$status || !$catatan) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Schedule gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        redirect("pages/schedule");
    }

    try {
        $pdo->beginTransaction();

        // Tentukan status schedule
        $statusToUpdate = !empty($issue_id) ? "Rescheduled" : $status;

        // Update schedules
        $sqlSchedule = "UPDATE schedules 
                            SET tech_id   = :tech_id, 
                                date      = :date, 
                                time      = :time, 
                                status    = :status,
                                catatan    = :catatan
                            WHERE schedule_key = :schedule_key";
        $stmt = $pdo->prepare($sqlSchedule);
        $stmt->execute([
            ':tech_id'          => $tech_id,
            ':date'          => $date,
            ':time'          => $time,
            ':status'        => $statusToUpdate,
            ':catatan'   => $catatan,
            ':schedule_key'   => $schedule_key
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
        redirect("pages/schedule/");
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
        redirect("pages/schedule/");
    }
} else {
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Gagal melakukan update, silakan coba lagi',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    redirect("pages/schedule");
}
