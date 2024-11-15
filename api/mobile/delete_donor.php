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

// Validasi input
if (empty($id)) {
    echo json_encode(array("status" => "error", "message" => "ID tidak ditemukan"));
    exit();
}

// Query untuk menghapus data pendonor
$sql = "DELETE FROM data_pendonor WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(array("status" => "success", "message" => "Data berhasil dihapus"));
} else {
    echo json_encode(array("status" => "error", "message" => "Gagal menghapus data"));
}

// Tutup koneksi
$stmt->close();
$conn->close();
?>
