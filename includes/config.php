<?php

session_start();

// $host = "localhost";
// $user = "root";
// $pass = "";
// $dbname   = "cms_isp";

define('BASE_URL', 'http://cms-project.test/');
$users = [
    "report" => "232323",
    "jon" => "654321",
    "joy" => "987654"
];


// try {
//     // Koneksi pakai PDO
    // $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//     // Optional: mode fetch default jadi associative array
//     $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
// } catch (PDOException $e) {
//     die("Koneksi database gagal: " . $e->getMessage());
// }
