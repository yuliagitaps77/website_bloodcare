<?php
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$username = "root";
$password = "";
$dbname = "bloodcarec3";

// Koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Koneksi gagal: " . $conn->connect_error]));
}

// Ambil ID akun (jika diperlukan)
$id = isset($_GET['id']) ? intval($_GET['id']) : 1; // Default ID = 1

// Query untuk mengambil data akun
$sql = "SELECT email, username, nama_lengkap, tanggal_lahir, no_handphone, alamat FROM akun WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Periksa apakah data ditemukan
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo json_encode(["success" => true, "data" => $data]);
} else {
    echo json_encode(["success" => false, "message" => "Data tidak ditemukan"]);
}

// Tutup koneksi
$conn->close();
?>