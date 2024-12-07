<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

if ($conn->connect_error) {
    die(json_encode(array("status" => "error", "message" => "Connection failed: " . $conn->connect_error)));
}

// Ambil parameter pencarian dari permintaan
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Query pencarian
$sql = "SELECT * FROM data_pendonor WHERE nama_pendonor LIKE ?";
$stmt = $conn->prepare($sql);
$search = "%$query%";
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode(array("status" => "success", "data" => $data));
} else {
    echo json_encode(array("status" => "error", "message" => "No data found"));
}

// Tutup koneksi
$stmt->close();
$conn->close();
?>