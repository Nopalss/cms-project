<?php
header('Content-Type: application/json');
require_once __DIR__ . "/../includes/config.php";

// Ambil input POST
$date    = $_POST['date']    ?? null;
$tech_id = $_POST['tech_id'] ?? null;

// Kalau data tidak lengkap → return array kosong
if (!$date || !$tech_id) {
    echo json_encode([]);
    exit;
}

// Daftar jam kerja (default)
$jamKerja = [
    "08:00",
    "09:00",
    "10:00",
    "11:00",
    "13:00",
    "14:00",
    "15:00",
    "16:00"
];

// Ambil jam yang sudah terpakai
$sql = "SELECT time FROM schedules WHERE tech_id = ? AND date = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$tech_id, $date]);

$jamTerpakai = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Normalisasi format → ambil hanya HH:MM
$jamTerpakai = array_map(fn($t) => substr($t, 0, 5), $jamTerpakai);

// Hitung jam kosong = jam kerja - jam terpakai
$jamKosong = array_values(array_diff($jamKerja, $jamTerpakai));

// Kirim hasil dalam format JSON
echo json_encode($jamKosong);
