<?php
require_once __DIR__ . '/../../includes/config.php';
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    // --- Ambil data POST ---
    $username = isset($_POST['username']) ? sanitize($_POST['username']) : null;
    $name     = isset($_POST['name']) ? sanitize($_POST['name']) : null;
    $phone    = isset($_POST['phone']) ? sanitize($_POST['phone']) : null;
    $passwordRaw = isset($_POST['password']) ? trim($_POST['password']) : null; // 
    $role     = isset($_POST['role']) ? sanitize($_POST['role']) : null;
    $check = $pdo->prepare("SELECT 1 FROM users WHERE username = :username");
    $check->execute([':username' => $username]);
    if ($check->fetchColumn()) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops!',
            'text' => 'Username sudah digunakan.',
            'button' => 'Coba Lagi',
            'style' => 'danger'
        ];
        header("Location: " . BASE_URL . "pages/user/create.php");
        exit;
    }
    // --- Validasi field wajib ---
    $required = compact('username', 'name', 'phone', 'passwordRaw', 'role');
    foreach ($required as $field => $value) {
        if (empty($value)) {
            $_SESSION['alert'] = [
                'icon'   => 'error',
                'title'  => 'Oops!',
                'text'   => "Field <b>$field</b> tidak boleh kosong.",
                'button' => 'Coba Lagi',
                'style'  => 'danger'
            ];
            header("Location: " . BASE_URL . "pages/user/create.php");
            exit;
        }
    }

    if (!preg_match('/^(08[0-9]{8,11}|62[0-9]{9,12})$/', $phone)) {
        $_SESSION['alert'] = [
            'icon'   => 'error',
            'title'  => 'Oops!',
            'text'   => "Nomor telepon harus berupa angka.",
            'button' => 'Coba Lagi',
            'style'  => 'danger'
        ];
        header("Location: " . BASE_URL . "pages/user/create.php");
        exit;
    }

    // --- Hash Password ---
    function hashPassword(string $plain): string
    {
        return password_hash($plain, PASSWORD_DEFAULT);
    }
    $password = hashPassword($passwordRaw);

    try {
        // Insert ke tabel users
        $sql = "INSERT INTO users (username,password,role)
                VALUES (:username,:password,:role)";
        $stmt = $pdo->prepare($sql);
        $user_success = $stmt->execute([
            ':username' => $username,
            ':password' => $password,
            ':role'     => $role
        ]);

        if ($user_success) {
            // Mapping role ke tabel
            $roles = [
                "admin" => ['table' => 'admin',    'id' => 'admin_id', 'prefix' => 'A'],
                "teknisi" => ['table' => 'technician', 'id' => 'tech_id',  'prefix' => 'T']
            ];

            if (isset($roles[$role])) {
                $table = $roles[$role]['table'];
                $idCol = $roles[$role]['id'];
                $prefix = $roles[$role]['prefix'];

                $id = $prefix . date("YmdHs");

                $sql = "INSERT INTO $table ($idCol, name, phone, username)
                        VALUES (:id,:name,:phone,:username)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':id'       => $id,
                    ':name'     => $name,
                    ':phone'    => $phone,
                    ':username' => $username
                ]);
            }
        }

        $_SESSION['alert'] = [
            'icon'  => 'success',
            'title' => 'Selamat!',
            'text'  => 'Pembuatan Akun Sukses',
            'button' => 'Oke',
            'style' => 'success'
        ];
    } catch (PDOException $e) {
        $_SESSION['alert'] = [
            'icon'   => 'error',
            'title'  => 'Error!',
            'text'   => 'Gagal menyimpan data. Error: ' . $e->getMessage(),
            'button' => 'Coba Lagi',
            'style'  => 'danger'
        ];
    }

    header("Location: " . BASE_URL . "pages/user/");
    exit;
}
