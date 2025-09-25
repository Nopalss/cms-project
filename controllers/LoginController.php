<?php
require_once __DIR__ . '/../includes/config.php';

// Jika sudah login, arahkan ke dashboard
if (isset($_SESSION['username'])) {
    if (isset($_SESSION['role']) == "teknisi") {
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    }
    header("Location: " . BASE_URL . "pages/dashboard.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        try {
            // Ambil data user dari database
            $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $table = $user['role'] == "admin" ? "admin" : "technician";
                $sql = "SELECT * FROM $table WHERE username = :username LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':username' => $username]);
                $karyawan = $stmt->fetch(PDO::FETCH_ASSOC);
                // Set session
                $id = $user['role'] == "admin" ? "admin_id" : "tech_id";
                $_SESSION['id_karyawan']  = $karyawan[$id];
                $_SESSION['name']  = $karyawan['name'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $user['role'];
                $_SESSION['alert'] = [
                    'icon' => 'success',
                    'title' => 'Login Berhasil',
                    'text' => 'Selamat datang kembali!',
                    'button' => "Masuk",
                    'style' => "success"
                ];

                if ($user['role'] == 'admin') {
                    header("Location: " . BASE_URL . "pages/dashboard.php");
                    exit;
                }
                header("Location: " . BASE_URL . "pages/schedule/");
                exit;
            } else {
                $_SESSION['alert'] = [
                    'icon' => 'error',
                    'title' => 'Login Gagal',
                    'text' => 'Username atau password salah!',
                    'button' => "Coba Lagi",
                    'style' => "danger"
                ];
            }
        } catch (PDOException $e) {
            $_SESSION['alert'] = [
                'icon' => 'warning', // bisa 'error' juga
                'title' => 'Terjadi Kesalahan',
                'text' => 'Silakan coba lagi nanti. Error: ' . $e->getMessage(),
                'button' => "Coba Lagi",
                'style' => "danger"
            ];
            header("Location: " . BASE_URL);
            exit;
        }
    } else {
        $_SESSION['alert'] = [
            'icon' => 'warning', // bisa 'error' juga
            'title' => 'Username atau password harus diisi',
            'text' => 'Silakan coba lagi',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL);
        exit;
    }
}
