<?php

require_once __DIR__ . "/../../../includes/config.php";

var_dump($_POST);
if (isset($_POST['submit'])) {
    // Fungsi sanitize untuk cegah HTML Injection
    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    $rm_id   = isset($_POST['rm_id']) ? sanitize($_POST['rm_id']) : null;
    $netpay_id   = isset($_POST['netpay_id']) ? sanitize($_POST['netpay_id']) : null;
    $type_issue   = isset($_POST['type_issue']) ? sanitize($_POST['type_issue']) : null;
    $deskripsi_issue   = isset($_POST['deskripsi_issue']) ? sanitize($_POST['deskripsi_issue']) : null;
    $request_by   = $_SESSION['username'];

    if (!$rm_id || !$netpay_id || !$type_issue || !$deskripsi_issue) {
        $_SESSION['alert'] = [
            'icon' => 'danger',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Update gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/request/maintenance/");
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Update request
        $sql = "UPDATE request_maintenance 
                SET netpay_id = :netpay_id, 
                    type_issue = :type_issue, 
                    deskripsi_issue = :deskripsi_issue, 
                    request_by = :request_by 
                WHERE rm_id = :rm_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":rm_id" => $rm_id,
            ":netpay_id" => $netpay_id,
            ":type_issue" => $type_issue,
            ":deskripsi_issue" => $deskripsi_issue,
            ":request_by" => $request_by
        ]);

        $pdo->commit();

        $_SESSION['alert'] = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Data Request berhasil diperbarui',
            'button' => "Oke",
            'style' => "success"
        ];
        header("Location: " . BASE_URL . "pages/request/maintenance/");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['alert'] = [
            'icon' => 'danger',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Silakan coba lagi nanti. Error: ' . $e->getMessage(),
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/request/maintenance/");
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
    header("Location: " . BASE_URL . "pages/request/maintenance/");
    exit;
}
