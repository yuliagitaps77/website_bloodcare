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
    // Check if Content-Type is application/json or x-www-form-urlencoded
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $input = json_decode(file_get_contents('php://input'), true);
        $email = isset($input['email']) ? $input['email'] : '';
        $new_password = isset($input['new_password']) ? $input['new_password'] : '';
    } else {
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    }

    // Validate input
    if (empty($email) || empty($new_password)) {
        echo json_encode(["status" => "error", "message" => "Email atau password tidak boleh kosong"]);
        exit;
    }

    // Check password length
    if (strlen($new_password) < 8) {
        echo json_encode(["status" => "error", "message" => "Password terlalu pendek. Harus lebih dari 8 karakter."]);
        exit;
    }

    // Escape and sanitize input data
    $email = $conn->real_escape_string($email);
    $new_password = password_hash($new_password, PASSWORD_BCRYPT); // Hashing password for security

    // Check if user exists
    $stmt = $conn->prepare("SELECT id_akun FROM akun WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update password
        $update_stmt = $conn->prepare("UPDATE akun SET password = ? WHERE email = ?");
        $update_stmt->bind_param("ss", $new_password, $email);
        if ($update_stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Password berhasil diperbarui"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error updating password: " . $conn->error]);
        }
        $update_stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>
