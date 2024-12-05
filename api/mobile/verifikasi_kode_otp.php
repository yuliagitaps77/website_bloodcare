<?php
header("Content-Type: application/json");

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cek metode permintaan apakah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari body request
    $data = json_decode(file_get_contents('php://input'), true);

    // Cek apakah email dan otp sudah dikirim di body
    if (isset($data['email']) && isset($data['otp'])) {
        $email = $data['email'];
        $otp = $data['otp'];

        // Ambil data akun berdasarkan email
        $stmt = $conn->prepare("SELECT otp, status_verify FROM akun WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Periksa apakah akun ditemukan
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Periksa apakah OTP cocok dan akun belum diverifikasi
            if ($row['otp'] == $otp && $row['status_verify'] == 0) {
                // Update status verifikasi menjadi 1 (terverifikasi)
                $update_stmt = $conn->prepare("UPDATE akun SET status_verify = 1 WHERE email = ?");
                $update_stmt->bind_param("s", $email);
                if ($update_stmt->execute()) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Verification successful!'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Failed to update verification status.'
                    ]);
                }
                $update_stmt->close();
            } else {
                // OTP tidak cocok atau sudah diverifikasi
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid OTP or account already verified.'
                ]);
            }
        } else {
            // Akun tidak ditemukan
            echo json_encode([
                'status' => 'error',
                'message' => 'Account not found.'
            ]);
        }
        $stmt->close();
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email and OTP are required.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method. Use POST.'
    ]);
}

// Tutup koneksi database
$conn->close();
?>
