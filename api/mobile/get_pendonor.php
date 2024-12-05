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

// Query untuk mengambil semua data pendonor
$sql = "SELECT id_pendonor, nama_pendonor, tanggal_lahir, no_telp, alamat, lokasi_donor, berat_badan, goldar, tekanan_darah, rhesus FROM data_pendonor";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row_status = "Lengkap"; // Set default status untuk setiap pendonor
        foreach ($row as $key => $value) {
            if (empty($value)) {
                $row_status = "Belum Lengkap"; // Ubah status jika ada kolom kosong
                break;
            }
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
