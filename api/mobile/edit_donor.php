<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Konfigurasi database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "bloodcare";

// Koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode(array("status" => "error", "message" => "Connection failed: " . $conn->connect_error)));
}

// Ambil data dari request
$id = isset($_POST['id']) ? $_POST['id'] : '';
$nama_pendonor = isset($_POST['nama_pendonor']) ? $_POST['nama_pendonor'] : '';
$golongan_darah = isset($_POST['golongan_darah']) ? $_POST['golongan_darah'] : '';
$alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';

// Validasi input
if (empty($id) || empty($nama_pendonor) || empty($golongan_darah) || empty($alamat)) {
    echo json_encode(array("status" => "error", "message" => "Data tidak lengkap"));
    exit();
}

// Query untuk mengupdate data pendonor
$sql = "UPDATE data_pendonor SET nama_pendonor = ?, golongan_darah = ?, alamat = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nama_pendonor, $golongan_darah, $alamat, $id);

if ($stmt->execute()) {
    echo json_encode(array("status" => "success", "message" => "Data berhasil diupdate"));
} else {
    echo json_encode(array("status" => "error", "message" => "Gagal mengupdate data"));
}

// Tutup koneksi
$stmt->close();
$conn->close();
?>
