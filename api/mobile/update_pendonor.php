<?php
header("Content-Type: application/json");

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php'; // Menggunakan koneksi.php yang sudah ada

// Ambil data dari request JSON
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id_pendonor'], $data['goldar'], $data['berat_badan'], $data['rhesus'], $data['tekanan_darah'])) {
    $id_pendonor = $data['id_pendonor'];
    $goldar = $data['goldar'];
    $berat_badan = $data['berat_badan'];
    $rhesus = $data['rhesus'];
    $tekanan_darah = $data['tekanan_darah'];

    // Perbarui data berdasarkan id_pendonor menggunakan mysqli
    $stmt = $conn->prepare("UPDATE data_pendonor SET goldar = ?, berat_badan = ?, rhesus = ?, tekanan_darah = ? WHERE id_pendonor = ?");
    $stmt->bind_param("ssssi", $goldar, $berat_badan, $rhesus, $tekanan_darah, $id_pendonor); // Menyusun parameter bind
    $stmt->execute();

    // Cek apakah data diubah
    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "Data berhasil diperbarui"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Data tidak ditemukan atau tidak ada perubahan"]);
    }

    // Menutup statement
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
}

$conn->close(); // Menutup koneksi ke database
?>
