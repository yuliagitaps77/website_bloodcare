<?php
header("Content-Type: application/json");

// Database credentials
$servername = "localhost";
$username = "bloodcar_e"; // Ganti dengan username database Anda
$password = "G_(Q+shgC2Nn"; // Ganti dengan password database Anda
$dbname = "bloodcar_e";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (!isset($input['nama_pendonor'], $input['kondisi_akun'], $input['id_akun'])) {
        echo json_encode(["status" => "error", "message" => "Invalid input"]);
        exit;
    }

    // Escape and sanitize input data
    $nama_pendonor = $conn->real_escape_string($input['nama_pendonor']);
    $kondisi_akun = $conn->real_escape_string($input['kondisi_akun']);
    $id_akun = (int) $input['id_akun'];

    // Insert data into the table
    $sql = "INSERT INTO sertifikat (nama_pendonor, kondisi_akun, id_akun)
            VALUES ('$nama_pendonor', '$kondisi_akun', $id_akun)";

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
