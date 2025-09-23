<?php

require_once __DIR__ . "/../../../includes/config.php";


var_dump($_POST);
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
            'text' => 'Request gagal. Pastikan semua data sudah diisi dengan benar.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/request/dismantle/");
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Insert request
        $sql = "INSERT INTO request_dismantle (rd_id, netpay_id, type_dismantle, deskripsi_dismantle, request_by)
            VALUES (:rd_id, :netpay_id, :type_dismantle, :deskripsi_dismantle, :request_by)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":rd_id" => $rd_id,
            ":netpay_id" => $netpay_id,
            ":type_dismantle" => $type_dismantle,
            ":deskripsi_dismantle" => $deskripsi_dismantle,
            ":request_by" => $request_by
        ]);

        // Insert queue
        $queue_id = "Q" . date("YmdHs");
        $sql = "INSERT INTO queue_scheduling (queue_id, type_queue, request_id) 
            VALUES (:queue_id, :type_queue, :request_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':queue_id' => $queue_id,
            ':type_queue' => "Dismantle",
            ':request_id' => $rd_id
        ]);

        $pdo->commit();

        $_SESSION['alert'] = [
            'icon' => 'success',
            'title' => 'Selamat!',
            'text' => 'Pendaftaran Request sukses',
            'button' => "Oke",
            'style' => "success"
        ];
        header("Location: " . BASE_URL . "pages/request/dismantle/");
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
        header("Location: " . BASE_URL . "pages/request/dismantle/");
        exit;
    }
} else {
    $_SESSION['alert'] = [
        'icon' => 'danger',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Gagal melakukan request, silakan coba lagi',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    header("Location: " . BASE_URL . "pages/request/dismantle/");
    exit;
}
