<?php
require_once __DIR__ . '/../../includes/config.php';

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Cek request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    // Ambil data POST & sanitasi
    $netpay_id      = isset($_POST['netpay_id']) ? sanitize($_POST['netpay_id']) : null;
    $name           = isset($_POST['name']) ? sanitize($_POST['name']) : null;
    $phone          = isset($_POST['phone']) ? sanitize($_POST['phone']) : null;
    $paket_internet = isset($_POST['paket_internet']) ? sanitize($_POST['paket_internet']) : null;
    $is_active      = isset($_POST['is_active']) ? sanitize($_POST['is_active']) : null;
    $location       = isset($_POST['location']) ? sanitize($_POST['location']) : null;

    // Validasi field wajib
    $requiredFields = [
        'netpay_id'      => $netpay_id,
        'name'           => $name,
        'phone'          => $phone,
        'paket_internet' => $paket_internet,
        'is_active'      => $is_active,
        'location'       => $location
    ];

    foreach ($requiredFields as $field => $value) {
        if (empty($value)) {
            $_SESSION['alert'] = [
                'icon'   => 'danger',
                'title'  => 'Oops!',
                'text'   => "Field <b>$field</b> tidak boleh kosong.",
                'button' => 'Coba Lagi',
                'style'  => 'danger'
            ];
            header("Location: " . BASE_URL . "pages/customers/detail.php?id=" . $netpay_id);
            exit;
        }
    }

    try {
        // Query update
        $sql = "UPDATE customers 
                SET name = :name,
                    phone = :phone,
                    paket_internet = :paket_internet,
                    is_active = :is_active,
                    location = :location
                WHERE netpay_id = :netpay_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':netpay_id'      => $netpay_id,
            ':name'           => $name,
            ':phone'          => $phone,
            ':paket_internet' => $paket_internet,
            ':is_active'      => $is_active,
            ':location'       => $location
        ]);

        $_SESSION['alert'] = [
            'icon'   => 'success',
            'title'  => 'Berhasil!',
            'text'   => 'Data customer berhasil diperbarui.',
            'button' => 'Oke',
            'style'  => 'success'
        ];
    } catch (PDOException $e) {
        $_SESSION['alert'] = [
            'icon'   => 'danger',
            'title'  => 'Error!',
            'text'   => 'Gagal update data. Error: ' . $e->getMessage(),
            'button' => 'Coba Lagi',
            'style'  => 'danger'
        ];
    }

    header("Location: " . BASE_URL . "pages/customers/");
    exit;
} else {
    $_SESSION['alert'] = [
        'icon' => 'warning',
        'title' => 'Oops!',
        'text' => 'Akses tidak valid.',
        'button' => "Oke",
        'style' => "warning"
    ];
    header("Location: " . BASE_URL . "pages/customers/");
    exit;
}
