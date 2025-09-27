<?php
require_once __DIR__ . "/../../includes/config.php";

if (isset($_POST['submit'])) {
    date_default_timezone_set('Asia/Jakarta');

    // Fungsi sanitize untuk cegah HTML Injection
    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    // Ambil & sanitasi data POST
    $issue_id   = isset($_POST['issue_id']) ? sanitize($_POST['issue_id']) : null;
    $issue_type = isset($_POST['issue_type']) ? sanitize($_POST['issue_type']) : null;
    $description = isset($_POST['description']) ? sanitize($_POST['description']) : null;
    $karyawan_id = $_SESSION['id_karyawan'];

    if (!$issue_id || !$karyawan_id || !$issue_type || !$description) {
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'Oops!',
            'text' => 'Data tidak lengkap, update dibatalkan.',
            'button' => "Oke",
            'style' => "warning"
        ];
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    }

    try {
        $sql = "UPDATE issues_report 
                SET issue_type = :issue_type, description = :description
                WHERE issue_id = :issue_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':issue_id'   => $issue_id,
            ':issue_type' => $issue_type,
            ':description' => $description,
        ]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['alert'] = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Issue Report berhasil diperbarui.',
                'button' => "Oke",
                'style' => "success"
            ];
        } else {
            $_SESSION['alert'] = [
                'icon' => 'info',
                'title' => 'Tidak Ada Perubahan',
                'text' => 'Data Issue Report tidak berubah.',
                'button' => "Oke",
                'style' => "info"
            ];
        }

        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    } catch (PDOException $e) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Gagal memperbarui Issue Report. Error: ' . $e->getMessage(),
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    }
} else {
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops!',
        'text' => 'Akses tidak valid.',
        'button' => "Oke",
        'style' => "warning"
    ];
    header("Location: " . BASE_URL . "pages/schedule/");
    exit;
}
