<?php
header("Content-Type: application/json");
session_start();

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (!isset($input['username_or_email'], $input['password'])) {
        echo json_encode(["status" => "error", "message" => "Invalid input"]);
        exit();
    }

    $usernameOrEmail = $conn->real_escape_string($input['username_or_email']);
    $password = $input['password']; // Tidak perlu escape karena akan diverifikasi dengan password_verify

    // Query to check user credentials
    $sql = "SELECT * FROM akun WHERE (username = ? OR email = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifikasi password dengan password_verify
        if (password_verify($password, $row['password'])) {
            // Check if profile is complete
            $isComplete = !empty($row['nama_lengkap']) && !empty($row['no_hp']) && !empty($row['alamat']) && !empty($row['tanggal_lahir']);

            // Tambahkan username dan email ke response
            $response = [
                "status" => "success",
                "redirect_to" => $isComplete ? "main_activity" : "page_editprofil",
                "user_id" => $row['id_akun'],
                "username" => $row['username'],
                "email" => $row['email']
            ];

            echo json_encode($response);
        } else {
            // Jika password salah
            echo json_encode(["status" => "error", "message" => "Password salah"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Username atau email tidak ditemukan"]);
    }

    // Tutup statement
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

// Tutup koneksi
$conn->close();
?>
