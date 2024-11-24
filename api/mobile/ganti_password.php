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
    if (!isset($input['email'], $input['old_password'], $input['new_password'])) {
        echo json_encode(["status" => "error", "message" => "Invalid input"]);
        exit;
    }

    // Escape and sanitize input data
    $username = $conn->real_escape_string($input['email']);
    $old_password = $input['old_password']; // Do not hash old_password here, it will be compared later
    $new_password = password_hash($input['new_password'], PASSWORD_BCRYPT);

    // Check if user exists and verify old password
    $sql = "SELECT password FROM akun WHERE email = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verifikasi password lama
        if (password_verify($old_password, $row['password'])) {
            // Update password
            $update_sql = "UPDATE akun SET password = '$new_password' WHERE email = '$username'";
            if ($conn->query($update_sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Password updated successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating password: " . $conn->error]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Old password is incorrect"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$conn->close();
?>
