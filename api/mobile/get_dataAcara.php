<?php

header("Content-Type: application/json; charset=UTF-8");

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

// Periksa koneksi
if ($conn->connect_error) {
    echo json_encode (["success" => false, "message" => "Koneksi gagal: " . $conn->connect_error]);
    exit();
}

// Query untuk mengambil data
$sql = "SELECT * FROM acara_donor";
$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; // Masukkan semua data ke dalam array
    }
    echo json_encode(["success" => true, "data" => $data]);
} else {
    echo json_encode(["success" => false, "message" => "Tidak ada data ditemukan."]);
}

// Tutup koneksi
$conn->close();

?>
