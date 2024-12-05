<?php
// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

// Periksa koneksi
if (!$conn) {
    die(json_encode(array("success" => false, "message" => "Koneksi ke database gagal")));
}

// Periksa apakah parameter `id` tersedia dalam permintaan POST
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Query untuk menghapus data berdasarkan ID
    $query = "DELETE FROM acara_donor WHERE id_acara = ?"; // Sesuaikan nama tabel dan kolom sesuai database Anda
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id); // Menggunakan prepared statement untuk keamanan
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(array("success" => true, "message" => "Data berhasil dihapus"));
        } else {
            echo json_encode(array("success" => false, "message" => "Gagal menghapus data"));
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(array("success" => false, "message" => "Kesalahan pada query SQL"));
    }
} else {
    // Jika parameter `id` tidak ditemukan
    echo json_encode(array("success" => false, "message" => "Parameter ID tidak ditemukan"));
}

// Tutup koneksi
mysqli_close($conn);
?>
