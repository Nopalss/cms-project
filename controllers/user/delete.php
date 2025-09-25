<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . "/../../includes/check_password.php";

date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    // Username wajib
    $username = isset($_POST['id']) ? sanitize($_POST['id']) : null;
    $password = trim($_POST['password'] ?? '');

    if (!$username || !$password) {
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'Oops!',
            'text' => 'Data tidak lengkap.',
            'button' => "Coba Lagi",
            'style' => "warning"
        ];
        header("Location: " . BASE_URL . "pages/user/");
        exit;
    }

    $user = checkLogin($pdo, $username, $password);
    if (!$user) {
        $_SESSION['alert'] = [
            'icon' => 'danger',
            'title' => 'Oops!',
            'text' => 'Password salah.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/user/");
        exit;
    }


    if (empty($username)) {
        $_SESSION['alert'] = [
            'icon'   => 'danger',
            'title'  => 'Oops!',
            'text'   => 'Username tidak boleh kosong.',
            'button' => 'Coba Lagi',
            'style'  => 'danger'
        ];
        header("Location: " . BASE_URL . "pages/user/");
        exit;
    }

    try {
        // Ambil role user dulu (agar tahu tabel mana yang akan dihapus)
        $stmt = $pdo->prepare("SELECT role FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("User tidak ditemukan.");
        }

        $role = $user['role'];

        // Mapping role -> tabel spesifik
        $roles = [
            "admin"   => 'admin',
            "teknisi" => 'technician'
        ];

        $pdo->beginTransaction();

        // Hapus dari tabel role (jika ada)
        if (isset($roles[$role])) {
            $table = $roles[$role];
            $stmt = $pdo->prepare("DELETE FROM $table WHERE username = :username");
            $stmt->execute([':username' => $username]);
        }

        // Hapus dari tabel users
        $stmt = $pdo->prepare("DELETE FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);

        $pdo->commit();

        $_SESSION['alert'] = [
            'icon'   => 'success',
            'title'  => 'Berhasil!',
            'text'   => 'User berhasil dihapus.',
            'button' => 'Oke',
            'style'  => 'success'
        ];
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['alert'] = [
            'icon'   => 'danger',
            'title'  => 'Error!',
            'text'   => 'Gagal menghapus data. Error: ' . $e->getMessage(),
            'button' => 'Coba Lagi',
            'style'  => 'danger'
        ];
    }

    header("Location: " . BASE_URL . "pages/user/");
    exit;
}
