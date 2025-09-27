<?php

require_once __DIR__ . '/../../includes/config.php';
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    // --- Ambil data POST ---
    $username  = isset($_POST['username']) ? sanitize($_POST['username']) : null; // primary key
    $name       = isset($_POST['name']) ? sanitize($_POST['name']) : null;
    $phone      = isset($_POST['phone']) ? sanitize($_POST['phone']) : null;
    $passwordRaw = isset($_POST['password']) ? trim($_POST['password']) : null;
    $role       = isset($_POST['role']) ? sanitize($_POST['role']) : null;

    // --- Validasi field wajib ---
    $required = compact('username', 'name', 'phone', 'role');
    foreach ($required as $field => $value) {
        if (empty($value)) {
            $_SESSION['alert'] = [
                'icon'   => 'danger',
                'title'  => 'Oops!',
                'text'   => "Field <b>$field</b> tidak boleh kosong.",
                'button' => 'Coba Lagi',
                'style'  => 'danger'
            ];
            header("Location: " . BASE_URL . "pages/user/edit.php?username=$username");
            exit;
        }
    }

    if (!preg_match('/^(08\d{8,11}|62\d{8,11})$/', $phone)) {
        $_SESSION['alert'] = [
            'icon'   => 'danger',
            'title'  => 'Oops!',
            'text'   => "Nomor telepon harus berupa angka.",
            'button' => 'Coba Lagi',
            'style'  => 'danger'
        ];
        header("Location: " . BASE_URL . "pages/user/edit.php?username=$username");
        exit;
    }

    // --- Hash Password jika diisi ---
    $paramsUser = [
        ':username' => $username,
        ':role'     => $role
    ];
    $sqlUser = "UPDATE users SET role = :role";

    if (!empty($passwordRaw)) {
        $sqlUser .= ", password = :password";
        $paramsUser[':password'] = password_hash($passwordRaw, PASSWORD_DEFAULT);
    }
    $sqlUser .= " WHERE username = :username";

    try {
        // Update tabel users
        $stmt = $pdo->prepare($sqlUser);
        $stmt->execute($paramsUser);

        // Mapping role ke tabel
        $roles = [
            "admin"   => ['table' => 'admin',      'username' => 'username'],
            "teknisi" => ['table' => 'technician', 'username' => 'username']
        ];

        if (isset($roles[$role])) {
            $table = $roles[$role]['table'];
            $sql = "UPDATE $table 
                    SET name = :name, phone = :phone
                    WHERE username = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name'     => $name,
                ':phone'    => $phone,
                ':username' => $username
            ]);
        }

        $_SESSION['alert'] = [
            'icon'  => 'success',
            'title' => 'Berhasil!',
            'text'  => 'Data user berhasil diupdate.',
            'button' => 'Oke',
            'style' => 'success'
        ];
    } catch (PDOException $e) {
        $_SESSION['alert'] = [
            'icon'  => 'danger',
            'title' => 'Error!',
            'text'  => 'Gagal update data. Error: ' . $e->getMessage(),
            'button' => 'Coba Lagi',
            'style' => 'danger'
        ];
    }

    header("Location: " . BASE_URL . "pages/user/");
    exit;
}
