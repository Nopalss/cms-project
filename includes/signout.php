<?php
require_once __DIR__ . '/config.php';

session_unset();
$_SESSION['success'] = "Anda telah berhasil logout.";
header("Location: " . BASE_URL);
exit;
