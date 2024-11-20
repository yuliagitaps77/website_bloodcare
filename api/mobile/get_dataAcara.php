<?php
header("Content-Type: application/json; charset=UTF-8");
$host = "localhost";
$username = "root";
$password = "";
$dbname = "bloodcarec3";

// Koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Koneksi gagal: " . $conn->connect_error]));
}

$sql = "SELECT * FROM acara_donor";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode(["success" => true, "data" => $data]);
$conn->close();
?>
