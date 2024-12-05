<?php
// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

// Cek koneksi
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Mengambil data dari request POST
$username_baru = isset($_POST['username_baru']) ? $_POST['username_baru'] : '';
$no_telepon = isset($_POST['no_telepon']) ? $_POST['no_telepon'] : '';
$alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
$nama_lengkap = isset($_POST['nama_lengkap']) ? $_POST['nama_lengkap'] : '';
$tanggal_lahir = isset($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : '';
$username = isset($_POST['username']) ? $_POST['username'] : ''; // Username lama

// Memeriksa apakah semua parameter yang diperlukan ada
if (empty($username) || empty($username_baru) || empty($no_telepon) || empty($alamat) || empty($nama_lengkap) || empty($tanggal_lahir)) {
    echo json_encode(["success" => false, "message" => "Data tidak lengkap"]);
    exit;
}

// Menyiapkan query untuk mengupdate data
$sql = "UPDATE akun SET username = ?, no_hp = ?, alamat = ?, nama_lengkap = ?, tanggal_lahir = ? WHERE username = ?";

// Menyiapkan statement
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error preparing statement: " . $conn->error]);
    exit;
}

// Bind parameter dengan tipe data
$stmt->bind_param("ssssss", $username_baru, $no_telepon, $alamat, $nama_lengkap, $tanggal_lahir, $username);

// Menjalankan query
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Data berhasil diperbarui"]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal memperbarui data: " . $stmt->error]);
}

// Menutup statement dan koneksi
$stmt->close();
$conn->close();
?>
