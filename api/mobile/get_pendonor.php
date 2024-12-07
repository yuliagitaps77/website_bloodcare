<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode(array("status" => "error", "message" => "Connection failed: " . $conn->connect_error)));
}

// Ambil parameter pencarian dari URL jika ada
$nama_pendonor = isset($_GET['nama_pendonor']) ? $_GET['nama_pendonor'] : '';

// Query untuk mengambil data pendonor berdasarkan nama pendonor
if ($nama_pendonor) {
    $sql = "SELECT id_pendonor, nama_pendonor, tanggal_lahir, no_telp, alamat, lokasi_donor, berat_badan, goldar, tekanan_darah, rhesus, id_akun FROM data_pendonor WHERE nama_pendonor LIKE '%$nama_pendonor%'";
} else {
    $sql = "SELECT id_pendonor, nama_pendonor, tanggal_lahir, no_telp, alamat, lokasi_donor, berat_badan, goldar, tekanan_darah, rhesus, id_akun FROM data_pendonor";
}

$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row_status = "Lengkap"; // Set default status untuk setiap pendonor

        // Periksa kolom yang spesifik: goldar, rhesus, berat_badan, tekanan_darah
        if (empty($row['goldar']) || empty($row['rhesus']) || empty($row['berat_badan']) || empty($row['tekanan_darah'])) {
            $row_status = "Belum Lengkap";
        }

        $row['status'] = $row_status; // Tambahkan status ke data pendonor
        $data[] = $row;
    }
    echo json_encode(array("status" => "success", "data" => $data));
} else {
    http_response_code(404);
    echo json_encode(array("status" => "error", "message" => "No data found"));
}

// Tutup koneksi
$conn->close();
?>
