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

// Ambil parameter bulan dari query string
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : null;

// Query dasar
$sql = "SELECT nama_pendonor, lokasi_donor, no_telp, berat_badan, goldar, tekanan_darah, rhesus, tgl_acara 
        FROM data_pendonor";

// Tambahkan filter bulan jika parameter tersedia
if ($bulan && $bulan !== "all") {
    $sql .= " WHERE MONTH(tgl_acara) = ?";
}

// Siapkan pernyataan
$stmt = $conn->prepare($sql);

// Bind parameter jika ada filter bulan
if ($bulan && $bulan !== "all") {
    $stmt->bind_param("i", $bulan);
}

// Eksekusi query
$stmt->execute();
$result = $stmt->get_result();

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
            "message" => "No records found for the selected month."
        ]);
    }
} else {
    // Menampilkan error query untuk debugging
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Query error: " . $stmt->error
    ]);
}

// Tutup koneksi
$stmt->close();
$conn->close();
?>
