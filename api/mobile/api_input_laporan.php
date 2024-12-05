<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

// Membaca body request JSON
$data = json_decode(file_get_contents("php://input"), true);

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

// Memeriksa apakah data ada
if ($data === null) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON data"]);
    exit();
}

// Mengambil data dari JSON yang diterima
$nama_pendonor = $data['nama_pendonor'] ?? null;
$lokasi_donor = $data['lokasi_donor'] ?? null;
$no_telp = $data['no_telp'] ?? null;
$berat_badan = $data['berat_badan'] ?? null;
$goldar = $data['goldar'] ?? null;
$tekanan_darah = $data['tekanan_darah'] ?? null;
$rhesus = $data['rhesus'] ?? null;

// Validasi input
if (!$nama_pendonor || !$lokasi_donor || !$no_telp || !$berat_badan || !$goldar || !$tekanan_darah || !$rhesus) {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
    exit();
}

// Query untuk memasukkan data laporan
$query = "INSERT INTO laporan (nama_pendonor, lokasi_donor, no_telp, berat_badan, goldar, tekanan_darah, rhesus) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

if ($stmt) {
    // Binding parameter
    $stmt->bind_param("sssssss", $nama_pendonor, $lokasi_donor, $no_telp, $berat_badan, $goldar, $tekanan_darah, $rhesus);

    // Eksekusi query
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Laporan berhasil ditambahkan"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Query error: " . $stmt->error]);
    }
    
    // Menutup prepared statement
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Failed to prepare query: " . $conn->error]);
}

// Menutup koneksi database
$conn->close();

?>
