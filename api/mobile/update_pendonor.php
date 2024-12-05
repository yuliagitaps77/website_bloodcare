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

    // Perbarui data pada tabel data_pendonor
    $stmt = $conn->prepare("UPDATE data_pendonor SET goldar = ?, berat_badan = ?, rhesus = ?, tekanan_darah = ? WHERE id_pendonor = ?");
    $stmt->bind_param("ssssi", $goldar, $berat_badan, $rhesus, $tekanan_darah, $id_pendonor);
    $stmt->execute();

    // Cek apakah data berhasil diubah
    if ($stmt->affected_rows > 0) {
        // Lakukan update stok_darah
        $jenis_darah = 'WB'; // Asumsikan jenis darah (bisa disesuaikan dengan logika yang diperlukan)

        // Cek apakah stok darah dengan golongan darah dan rhesus ini sudah ada
        $check_stmt = $conn->prepare("SELECT stok FROM stok_darah WHERE goldar = ? AND rhesus = ? AND jenis_darah = ?");
        $check_stmt->bind_param("sss", $goldar, $rhesus, $jenis_darah);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // Jika stok darah sudah ada, tambahkan stok
            $update_stmt = $conn->prepare("UPDATE stok_darah SET stok = stok + 1, tanggal_pembaruan = NOW() WHERE goldar = ? AND rhesus = ? AND jenis_darah = ?");
            $update_stmt->bind_param("sss", $goldar, $rhesus, $jenis_darah);
            $update_stmt->execute();
            $update_stmt->close();
        } else {
            // Jika stok darah belum ada, masukkan data baru
            $insert_stmt = $conn->prepare("INSERT INTO stok_darah (jenis_darah, goldar, rhesus, stok, tanggal_pembaruan) VALUES (?, ?, ?, 1, NOW())");
            $insert_stmt->bind_param("sss", $jenis_darah, $goldar, $rhesus);
            $insert_stmt->execute();
            $insert_stmt->close();
        }

        $check_stmt->close();

        echo json_encode(["status" => "success", "message" => "Data pendonor dan stok darah berhasil diperbarui"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Data tidak ditemukan atau tidak ada perubahan"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
}

$conn->close(); // Menutup koneksi ke database
?>
