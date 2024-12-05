<?php
header("Content-Type: application/json");

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get raw input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (!isset($input['lokasi'], $input['fasilitas'], $input['time_waktu'], $input['tgl_acara'])) {
        echo json_encode(["status" => "error", "message" => "Invalid input data"]);
        exit;
    }

    $lokasi = $conn->real_escape_string($input['lokasi']);
    $fasilitas = $conn->real_escape_string($input['fasilitas']);
    $time_waktu = $conn->real_escape_string($input['time_waktu']);
    $tgl_acara = $conn->real_escape_string($input['tgl_acara']);

    // Insert data
    $sql = "INSERT INTO acara_donor (lokasi, fasilitas, time_waktu, tgl_acara) VALUES ('$lokasi', '$fasilitas', '$time_waktu', '$tgl_acara')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Data inserted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>
