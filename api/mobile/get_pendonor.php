<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Konfigurasi database
$host = "localhost";
$username = "bloodcar_e";
$password = "G_(Q+shgC2Nn";
$dbname = "bloodcar_e";

// Koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {

    die(json_encode(array("status" => "error", "message" => "Connection failed: " . $conn->connect_error)));
}

// Query untuk mengambil semua data pendonor
$sql = "SELECT nama_pendonor, tanggal_lahir, no_telp, alamat, lokasi_donor, berat_badan, goldar, rhesus FROM data_pendonor";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
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
