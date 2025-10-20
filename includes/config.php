<?php

session_start();
date_default_timezone_set('Asia/Jakarta');
$host = "localhost";
$user = "root";
$pass = "";
$db = "cms_database";

define('BASE_URL', 'http://cms-project.test/');
$users = [
    "report" => "232323",
    "jon" => "654321",
    "joy" => "987654"
];


try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
    $pdo = new PDO($dsn, $user, $pass);

    // mode error → lempar exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
