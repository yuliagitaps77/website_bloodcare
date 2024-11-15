<?php
header("Content-Type: application/json");

// Database credentials
$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "bloodcare";

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
    if (!isset($input['goldar'], $input['rhesus'], $input['stok'], $input['komponen_darah'], $input['tanggal_pembaruan'])) {
        echo json_encode(["status" => "error", "message" => "Invalid input"]);
        exit;
    }

    // Escape and sanitize input data
    $goldar = $conn->real_escape_string($input['goldar']);
    $rhesus = $conn->real_escape_string($input['rhesus']);
    $stok = (int) $input['stok'];
    $komponen_darah = $conn->real_escape_string($input['komponen_darah']);
    $tanggal_pembaruan = $conn->real_escape_string($input['tanggal_pembaruan']);

    // Insert data into the table
    $sql = "INSERT INTO stok_darah (goldar, rhesus, stok, komponen_darah, tanggal_pembaruan)
            VALUES ('$goldar', '$rhesus', $stok, '$komponen_darah', '$tanggal_pembaruan')";

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
