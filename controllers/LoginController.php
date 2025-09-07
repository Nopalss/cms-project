<?php

require_once __DIR__ . '/../includes/config.php';

// if (isset($_SESSION['username'])) {
//     header("Location: " . BASE_URL . "pages/dashboard.php");
//     exit;
// }
$error = "";
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    global $users;
    if ($username && $password) {
        if (isset($users[$username]) && $password === $users[$username]) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'Admin';
            $_SESSION['success'] = "Login berhasil! Selamat datang, {$username}";
            header("Location: " . BASE_URL . "pages/dashboard.php");
            exit;
        }
        $_SESSION['error'] = "Username atau password salah";
        header("Location: " . BASE_URL);
        exit;
    }
    if (!$username || !$password) {
        $_SESSION['error'] = "Username atau password Harus Diisi";
        header("Location: " . BASE_URL);
        exit;
    }
}
