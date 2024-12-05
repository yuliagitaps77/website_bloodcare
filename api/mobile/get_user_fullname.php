<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Pastikan ini jika ingin akses dari Android

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

if ($conn->connect_error) {
    die(json_encode(array("error" => "Connection failed: " . $conn->connect_error)));
}

if (isset($_GET['username_or_email'])) {
    $input = $conn->real_escape_string($_GET['username_or_email']);
    $sql = "SELECT username FROM akun WHERE username = '$input' OR email = '$input'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Mengirimkan username yang ditemukan dalam respons
        echo json_encode(array("username" => $row['username']));
    } else {
        echo json_encode(array("error" => "User not found"));
    }
} else {
    echo json_encode(array("error" => "No username or email provided"));
}

$conn->close();
?>
