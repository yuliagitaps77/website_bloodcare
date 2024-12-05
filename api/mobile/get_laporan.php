<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

// Periksa koneksi
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit;
}

// Query untuk mengambil data
$sql = "SELECT nama_pendonor, lokasi_donor, no_telp, berat_badan, goldar, tekanan_darah, rhesus FROM data_pendonor";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "data" => $data
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "No records found in the table."
        ]);
    }
} else {
    // Menampilkan error query untuk debugging
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Query error: " . $conn->error,
        "query" => $sql
    ]);
}

// Tutup koneksi
$conn->close();
?>
