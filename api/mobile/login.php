<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

// Konfigurasi database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "bloodcare"; // Ganti dengan nama database Anda

// Koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    echo json_encode(array("status" => "error", "message" => "Connection failed"));
    exit();
}

// Mendapatkan data dari input JSON (username/email dan password)
$data = json_decode(file_get_contents("php://input"), true);
$user_input = $data["username_or_email"];
$password_input = $data["password"];

// Query untuk mengecek keberadaan akun dengan username/email dan password yang cocok
$sql = "SELECT * FROM akun WHERE (username = ? OR email = ?)"; // Hanya mencari username atau email, tidak memeriksa password
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user_input, $user_input);
$stmt->execute();
$result = $stmt->get_result();

// Mengecek hasil query
if ($result->num_rows > 0) {
    // Ambil data pengguna dari hasil query
    $user = $result->fetch_assoc();

    // Mengecek apakah password yang diberikan cocok dengan hash di database
    if (password_verify($password_input, $user['password'])) {
        // Jika password cocok
        echo json_encode(array("status" => "success", "message" => "Login successful", "data" => $user));
    } else {
        // Jika password tidak cocok
        echo json_encode(array("status" => "error", "message" => "Invalid username/email or password"));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Invalid username/email or password"));
}

// Tutup koneksi
$stmt->close();
$conn->close();
?>
