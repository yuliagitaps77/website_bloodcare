<?php
header("Content-Type: application/json");

// Debug awal untuk memastikan API berjalan
echo json_encode(["status" => "debug", "message" => "API berjalan"]);

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log input data
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        echo json_encode(["status" => "error", "message" => "No input received"]);
        exit;
    }

    // Debug input data
    echo json_encode(["status" => "debug", "message" => "Input diterima", "data" => $input]);

    // Validate input
    if (!isset(
        $input['nama_pendonor'], 
        $input['lokasi_donor'], 
        $input['hp_email'], 
        $input['tinggi_badan'], 
        $input['berat_badan'], 
        $input['goldar'], 
        $input['tekanan_darah']
    )) {
        echo json_encode(["status" => "error", "message" => "Invalid input"]);
        exit;
    }

    // Escape and sanitize input data
    $nama_pendonor = $conn->real_escape_string($input['nama_pendonor']);
    $lokasi_donor = $conn->real_escape_string($input['lokasi_donor']);
    $hp_email = $conn->real_escape_string($input['hp_email']);
    $tinggi_badan = (int) $input['tinggi_badan'];
    $berat_badan = (int) $input['berat_badan'];
    $goldar = $conn->real_escape_string($input['goldar']);
    $tekanan_darah = $conn->real_escape_string($input['tekanan_darah']);

    // Insert data into the table
    $sql = "INSERT INTO laporan (nama_pendonor, lokasi_donor, hp_email, tinggi_badan, berat_badan, goldar, tekanan_darah)
            VALUES ('$nama_pendonor', '$lokasi_donor', '$hp_email', $tinggi_badan, $berat_badan, '$goldar', '$tekanan_darah')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Data inserted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error, "sql" => $sql]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>
