<?php

require_once __DIR__ . "/../../../includes/config.php";

if (isset($_POST['submit'])) {
    // Fungsi sanitize untuk cegah HTML Injection
    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    $rd_id   = isset($_POST['rd_id']) ? sanitize($_POST['rd_id']) : null;
    $netpay_id   = isset($_POST['netpay_id']) ? sanitize($_POST['netpay_id']) : null;
    $type_dismantle   = isset($_POST['type_dismantle']) ? sanitize($_POST['type_dismantle']) : null;
    $deskripsi_dismantle   = isset($_POST['deskripsi_dismantle']) ? sanitize($_POST['deskripsi_dismantle']) : null;
    $request_by   = $_SESSION['username'];

    if (!$rd_id || !$netpay_id || !$type_dismantle || !$deskripsi_dismantle) {
        $_SESSION['alert'] = [
            'icon' => 'danger',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Update gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/request/dismantle/");
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Update request
        $sql = "UPDATE request_dismantle 
                SET netpay_id = :netpay_id, 
                    type_dismantle = :type_dismantle, 
                    deskripsi_dismantle = :deskripsi_dismantle, 
                    request_by = :request_by 
                WHERE rd_id = :rd_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":rd_id" => $rd_id,
            ":netpay_id" => $netpay_id,
            ":type_dismantle" => $type_dismantle,
            ":deskripsi_dismantle" => $deskripsi_dismantle,
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
        header("Location: " . BASE_URL . "pages/request/dismantle/");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log($e->getMessage()); // simpan di error log server
        $_SESSION['alert'] = [
            'icon' => 'danger',
            'title' => 'Oops! Ada yang Salah',
            'text' => 'Silakan coba lagi nanti.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/request/dismantle/");
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
    header("Location: " . BASE_URL . "pages/request/dismantle/");
    exit;
}
