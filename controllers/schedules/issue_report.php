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
    $schedule_id = isset($_POST['schedule_id']) ? sanitize($_POST['schedule_id']) : null;
    $issue_type = isset($_POST['issue_type']) ? sanitize($_POST['issue_type']) : null;
    $description = isset($_POST['description']) ? sanitize($_POST['description']) : null;
    $karyawan_id = $_SESSION['id_karyawan'];
    echo var_dump($karyawan_id);
    // Pastikan semua data terisi
    if (!$schedule_id || !$karyawan_id || !$issue_type || !$description) {
        $_SESSION['error'] = "Gagal menyimpan data, silakan coba lagi.";
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    }

    // Buat ID unik
    $issue_id = 'I' . date("YmdHs");
    try {
        // Query insert dengan prepared statement
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
        $_SESSION['success'] = "Issue Report berhasil dibuat dan tersimpan dengan aman";
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal menyimpan data, silakan coba lagi";
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    }
} else {
    $_SESSION['error'] = "Gagal menyimpan data, silakan coba lagi";
    header("Location: " . BASE_URL . "pages/schedule/");
    exit;
}
