<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Pastikan ini jika ingin akses dari Android

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

if ($conn->connect_error) {
    echo json_encode(array("status" => "error", "message" => "Connection failed: " . $conn->connect_error));
    exit;
}

// Cek apakah parameter username_or_email diberikan
if (isset($_GET['usernameOrEmail'])) {
    $input = $conn->real_escape_string($_GET['usernameOrEmail']);
    
    // Query untuk mendapatkan data user
    $sql = "SELECT nama_lengkap, email, no_hp FROM akun WHERE username = '$input' OR email = '$input'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Mengembalikan data user
        echo json_encode(array(
            "status" => "success",
            "data" => array(
                "nama_lengkap" => $row['nama_lengkap'],
                "email" => $row['email'],
                "no_hp" => $row['no_hp']
            )
        ));
    } else {
        // Data tidak ditemukan
        echo json_encode(array("status" => "error", "message" => "User not found"));
    }
} else {
    // Parameter tidak diberikan
    echo json_encode(array("status" => "error", "message" => "No username or email provided"));
}

$conn->close();
?>
